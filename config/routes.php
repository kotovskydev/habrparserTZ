<?php

return array(
    'parser/urls' => 'parser/parseurls',
    'parser/post' => 'parser/parsing',
    'create/async?[\s\S]+' => 'news/addASync',
    'search/posts' => 'news/search',
    'create/sync?[\s\S]+' => 'news/addSync',
    'add/new-post' => 'news/addpage',
    'news/([0-9]+)'=> 'news/index/$1',
    '([\s\S]+)'=> 'news/rubricpage/$1',
	'' => 'news/mainpage'
	
);