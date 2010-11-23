<?php
/**
 * 
 */
class App_Log {

    const LOGGER_DEST_DB = 'db';
    const LOGGER_DEST_FIREBUG = 'firebug';

    protected static $_loggers;
    protected static $_host;

    private static function init() {

        // lazy initialize the logger
        if (!isset(self::$_loggers)) {
            // configure each type of logger we will support
            $writer = new Zend_Log_Writer_Firebug();
            $fbLogger = new Zend_Log($writer);
            $db = Zend_Db_Table::getDefaultAdapter();
            $colMap = array(
                'message' => 'message',
                'host' => 'host',
                'level' => 'level',
                'created_at' => 'timestamp'
            );
            $writer = new Zend_Log_Writer_Db($db, 'log', $colMap);
            $dbLogger = new Zend_Log($writer);

            // read from the ini file to see which loggers are configured
            // for each log level
            $config = Zend_Registry::get('config');
            if ($config->applog) {
                foreach ($config->applog->level as $levelKey => $levelValue) {
                    foreach (explode(',', $levelValue) as $logKey) {
                        switch (trim($logKey)) {
                            case self::LOGGER_DEST_DB:
                                self::$_loggers[self::convertLevelToInt($levelKey)]
                                    [$logKey] = &$dbLogger;
                                break;
                            case self::LOGGER_DEST_FIREBUG:
                                self::$_loggers[self::convertLevelToInt($levelKey)]
                                    [$logKey] = &$fbLogger;
                                break;
                            case '':
                                // ignore
                                break;
                            default:
                                throw new Exception('unknown logger type [' .
                                    $logKey . ']');
                        }
                    }
                }
            }

            // get the current host name so we can include it in the log table
            self::$_host  = (isset($_SERVER['SERVER_ADDR'])
                ? $_SERVER['SERVER_ADDR']:'');
        }
    }

    public static function alert($message) {
        self::log($message, Zend_Log::ALERT);
    }

    public static function error($message) {
        self::log($message, Zend_Log::ERR);
    }

    public static function warn($message) {
        self::log($message, Zend_Log::WARN);
    }

    public static function info($message) {
        self::log($message, Zend_Log::INFO);
    }

    public static function debug($message) {
        self::log($message, Zend_Log::DEBUG);
    }

    private static function log($message, $level) {
        self::init();

        // loop through each logger configured for this level and
        //record the log message
        if (is_array(self::$_loggers) &&
            isset(self::$_loggers[$level]) &&
            sizeof(self::$_loggers[$level]) > 0) {
            foreach (self::$_loggers[$level] as &$logger) {
                $logger->setEventItem('level',
                    self::convertLevelToString($level));
                $logger->setEventItem('host', self::$_host);
                $logger->log($message, $level);
            }
        }
    }

    /*
     * Converts the the string log level into its Zend_Log numeric equivalent
     * @param string corresponding to the log level to convert
     * @return int corresponding to the Zend_Log numerical equivalent
     */
    private static function convertLevelToInt($level) {
        $const_name = 'Zend_Log::'. strtoupper($level);
        if (defined($const_name)) {
            return constant($const_name);
        }
        else {
            throw new Exception('invalid log level [' . $level . ']');
        }
    }

    /*
     * Converts the the Zend_Log numeric to its string log level equivalent
     * @param int corresponding to the numeric Zend_Log to convert
     * @return string corresponding to the log level equivalent
     */
    private static function convertLevelToString($level) {
        $reflect = new ReflectionClass("Zend_Log");
        $class_constants = $reflect->getConstants();
        foreach ($class_constants as $key => $val) {
            if ($val === $level) {
                return strtolower($key);
            }
        }

        // if we get this far, we don't have a log level that matches any constants in Zend_Log
        throw new Exception('invalid log level [' . $level . ']');

    }

}