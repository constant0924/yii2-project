<?php 
use kartik\tree\TreeView;
use kartik\icons\Icon;
Icon::map($this, Icon::FA);

$this->title = '分类管理';
echo TreeView::widget([
    // single query fetch to render the tree
    // use the Product model you have in the previous step
    'query' => $categorys, 
    'headingOptions' => ['label' => '分类管理'],
    'rootOptions' => ['label'=>'<span class="text-primary">根</span>'],
    'fontAwesome' => false,     // optional
    'isAdmin' => true,         // optional (toggle to enable admin mode)
    'displayValue' => 1,        // initial display value
    'softDelete' => true,       // defaults to true
    'cacheSettings' => [        
        'enableCache' => true   // defaults to true
    ],
    'mainTemplate' => '<div class="row"><div class="col-sm-4">{wrapper}</div><div class="col-sm-8">{detail}</div></div>',
    'iconEditSettings'=> [
        'show' => 'list',
        'listData' => [
            'folder-close' => 'Folder',
            'file' => 'File',
            'phone' => 'Phone',
            'bell' => 'Bell',
        ]
    ],
]);