<?php

return array(
    array(
       'label'         => 'nav_home',
       'title'         => 'nav_home',
       'module'        => 'frontend',
       'controller'=> 'index',
       'action'        => 'index',
       ),

       array(
       'label'         => 'nav_tasks',
       'title'         => 'nav_tasks',
       'module'        => 'frontend',
       'controller'    => 'task',
       'action'        => 'index',
       'resource'      => 'frontend/task',
       'role'          => 'user',
       )
);