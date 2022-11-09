<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    '' => ['HomeController', 'index',],
    'start-game' => ['GameController', 'startGame'],
    'question' => ['GameController', 'question'],
    'admin/signin' => ['AdminController', 'signIn',],
    'admin/signup' => ['AdminController', 'signUp',],
    'game' => ['GameController', 'index'],
    'admin/add' => ['QuestionController', 'add',],
    'admin/show' => ['QuestionController', 'index',],
    'admin/delete' => ['QuestionController', 'delete',],
    'admin/update' => ['QuestionController', 'update', ['id']],
    'admin/display' => ['QuestionController', 'display', ['id']],
    'items' => ['ItemController', 'index',],
    'items/edit' => ['ItemController', 'edit', ['id']],
    'items/show' => ['ItemController', 'show', ['id']],
    'items/add' => ['ItemController', 'add',],
    'items/delete' => ['ItemController', 'delete',],
];
