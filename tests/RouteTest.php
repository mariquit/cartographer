<?php

require_once 'Route.php';

class RouteTest extends PHPUnit_Framework_TestCase {

    // Test Setup and Cleanup
    public function setUp()
    {
        Route::reset();
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // The Basics
    //--------------------------------------------------------------------

    public function testIsLoaded()
    {
        $this->assertTrue(class_exists('Route'));
    }

    //--------------------------------------------------------------------


    public function test_any_works_like_ci_routes()
    {
        Route::any('posts/(:any)', 'posts/show/$1');
        Route::any('books', 'books/index');

        $data = array(
            'posts/(:any)'  => 'posts/show/$1',
            'books'         => 'books/index'
        );

        $this->assertEquals( $data, Route::map());
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // HTTP Verb Routing
    //--------------------------------------------------------------------

    public function test_get()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        Route::get('posts/(:any)', 'posts/show/$1');

        $data = array(
            'posts/(:any)'  => 'posts/show/$1'
        );

        $this->assertEquals( $data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_not_get()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        Route::get('posts/(:any)', 'posts/show/$1');

        $this->assertEquals( array(), Route::map());
    }

    //--------------------------------------------------------------------

    public function test_post()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        Route::post('posts/(:any)', 'posts/show/$1');

        $data = array(
            'posts/(:any)'  => 'posts/show/$1'
        );

        $this->assertEquals( $data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_put()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';

        Route::put('posts/(:any)', 'posts/show/$1');

        $data = array(
            'posts/(:any)'  => 'posts/show/$1'
        );

        $this->assertEquals( $data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_delete()
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';

        Route::delete('posts/(:any)', 'posts/show/$1');

        $data = array(
            'posts/(:any)'  => 'posts/show/$1'
        );

        $this->assertEquals( $data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_head()
    {
        $_SERVER['REQUEST_METHOD'] = 'HEAD';

        Route::head('posts/(:any)', 'posts/show/$1');

        $data = array(
            'posts/(:any)'  => 'posts/show/$1'
        );

        $this->assertEquals( $data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_patch()
    {
        $_SERVER['REQUEST_METHOD'] = 'PATCH';

        Route::patch('posts/(:any)', 'posts/show/$1');

        $data = array(
            'posts/(:any)'  => 'posts/show/$1'
        );

        $this->assertEquals( $data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_options()
    {
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';

        Route::options('posts/(:any)', 'posts/show/$1');

        $data = array(
            'posts/(:any)'  => 'posts/show/$1'
        );

        $this->assertEquals( $data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_resources_get_basics()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        Route::resources('photos');

        $data = array(
            'photos' => 'photos/index',
            'photos/new' => 'photos/create_new',
            'photos/([a-zA-Z0-9\-_]+)'  => 'photos/show/$1',
            'photos/([a-zA-Z0-9\-_]+)/edit'  => 'photos/edit/$1'
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_resources_post_basics()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        Route::resources('photos');

        $data = array(
            'photos' => 'photos/create',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_resources_put_basics()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';

        Route::resources('photos');

        $data = array(
            'photos/([a-zA-Z0-9\-_]+)' => 'photos/update/$1',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_resources_delete_basics()
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';

        Route::resources('photos');

        $data = array(
            'photos/([a-zA-Z0-9\-_]+)' => 'photos/delete/$1',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_resources_post_and_module()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        Route::resources('blog/photos');

        $data = array(
            'blog/photos' => 'blog/photos/create',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_resources_post_option_controller()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        Route::resources('photos', array('controller'=>'images'));

        $data = array(
            'photos' => 'images/create',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_resources_post_option_module_controller()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        Route::resources('photos', array('controller'=>'images', 'module'=>'gallery'));

        $data = array(
            'photos' => 'gallery/images/create',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_resources_put_option_constraint()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';

        Route::resources('photos', array('constraint' => '(:num)'));

        $data = array(
            'photos/(:num)' => 'photos/update/$1',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_resources_put_offset()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';

        Route::resources('photos', array('offset' => 1));

        $data = array(
            'photos/([a-zA-Z0-9\-_]+)' => 'photos/update/$2',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Prefixes
    //--------------------------------------------------------------------

    public function test_simple_prefix()
    {
        Route::prefix('galleries', function() {
            Route::any('photos', 'photos/index');
        });

        $data = array(
            'galleries/photos' => 'photos/index'
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Named Routes
    //--------------------------------------------------------------------

    public function test_named_routes()
    {
        Route::any('posts', 'posts/index', array('as' => 'blog'));

        $this->assertEquals('posts', Route::named('blog'));
    }

    //--------------------------------------------------------------------

    public function test_named_routes_with_prefix()
    {
        Route::prefix('area', function(){
            Route::any('posts', 'posts/index', array('as' => 'blog'));
        });

        $this->assertEquals('area/posts', Route::named('blog'));
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Contexts
    //--------------------------------------------------------------------

    public function test_context_name_only()
    {
        Route::context('tools');

        $data = array(
            'tools/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'   => '$1/tools/$2/$3/$4/$5/$6',
            'tools/(:any)/(:any)/(:any)/(:any)/(:any)'          => '$1/tools/$2/$3/$4/$5',
            'tools/(:any)/(:any)/(:any)/(:any)'                 => '$1/tools/$2/$3/$4',
            'tools/(:any)/(:any)/(:any)'                        => '$1/tools/$2/$3',
            'tools/(:any)/(:any)'                               => '$1/tools/$2',
            'tools/(:any)'                                      => '$1/tools',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_context_name_and_controller()
    {
        Route::context('tools', 'another');

        $data = array(
            'tools/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'   => '$1/another/$2/$3/$4/$5/$6',
            'tools/(:any)/(:any)/(:any)/(:any)/(:any)'          => '$1/another/$2/$3/$4/$5',
            'tools/(:any)/(:any)/(:any)/(:any)'                 => '$1/another/$2/$3/$4',
            'tools/(:any)/(:any)/(:any)'                        => '$1/another/$2/$3',
            'tools/(:any)/(:any)'                               => '$1/another/$2',
            'tools/(:any)'                                      => '$1/another',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_context_name_and_options()
    {
        Route::context('tools', array('offset' => 1));

        $data = array(
            'tools/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'   => '$2/tools/$3/$4/$5/$6/$7',
            'tools/(:any)/(:any)/(:any)/(:any)/(:any)'          => '$2/tools/$3/$4/$5/$6',
            'tools/(:any)/(:any)/(:any)/(:any)'                 => '$2/tools/$3/$4/$5',
            'tools/(:any)/(:any)/(:any)'                        => '$2/tools/$3/$4',
            'tools/(:any)/(:any)'                               => '$2/tools/$3',
            'tools/(:any)'                                      => '$2/tools',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_context_name_controller_options()
    {
        Route::context('tools', 'another', array('offset' => 1));

        $data = array(
            'tools/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'   => '$2/another/$3/$4/$5/$6/$7',
            'tools/(:any)/(:any)/(:any)/(:any)/(:any)'          => '$2/another/$3/$4/$5/$6',
            'tools/(:any)/(:any)/(:any)/(:any)'                 => '$2/another/$3/$4/$5',
            'tools/(:any)/(:any)/(:any)'                        => '$2/another/$3/$4',
            'tools/(:any)/(:any)'                               => '$2/another/$3',
            'tools/(:any)'                                      => '$2/another',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_context_name_controller_options_with_home()
    {
        Route::context('tools', 'another', array('offset' => 1, 'home' => '{default_controller}'));

        $data = array(
            'tools/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'   => '$2/another/$3/$4/$5/$6/$7',
            'tools/(:any)/(:any)/(:any)/(:any)/(:any)'          => '$2/another/$3/$4/$5/$6',
            'tools/(:any)/(:any)/(:any)/(:any)'                 => '$2/another/$3/$4/$5',
            'tools/(:any)/(:any)/(:any)'                        => '$2/another/$3/$4',
            'tools/(:any)/(:any)'                               => '$2/another/$3',
            'tools/(:any)'                                      => '$2/another',
            'tools'                                             => 'tools/home'
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_context_and_prefix()
    {
        Route::prefix('developer', function() {
            Route::context('tools');
        });

        $data = array(
            'developer/tools/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'   => '$1/tools/$2/$3/$4/$5/$6',
            'developer/tools/(:any)/(:any)/(:any)/(:any)/(:any)'          => '$1/tools/$2/$3/$4/$5',
            'developer/tools/(:any)/(:any)/(:any)/(:any)'                 => '$1/tools/$2/$3/$4',
            'developer/tools/(:any)/(:any)/(:any)'                        => '$1/tools/$2/$3',
            'developer/tools/(:any)/(:any)'                               => '$1/tools/$2',
            'developer/tools/(:any)'                                      => '$1/tools',
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Nested Routes
    //--------------------------------------------------------------------

    public function test_basic_routes_work_nested()
    {
        Route::any('posts/(:num)', 'posts/show/$1', null, function() {
            Route::any('comments', 'comments/show/$1');
            Route::any('authors', 'authors/show/$1');
        });

        $data = array(
            'posts/(:num)'          => 'posts/show/$1',
            'posts/(:num)/comments' => 'comments/show/$1',
            'posts/(:num)/authors'  => 'authors/show/$1'
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------

    public function test_resources_can_be_nested()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        Route::any('posts/(:num)', 'posts/$1', null, function() {
            Route::resources('comments');
        });

        $data = array(
            'posts/(:num)'                                  => 'posts/$1',
            'posts/(:num)/comments'                         => 'comments/index/$1',
            'posts/(:num)/comments/new'                     => 'comments/create_new/$1',
            'posts/(:num)/comments/([a-zA-Z0-9\-_]+)/edit'  => 'comments/edit/$1/$2',
            'posts/(:num)/comments/([a-zA-Z0-9\-_]+)'       => 'comments/show/$1/$2'
        );

        $this->assertEquals($data, Route::map());
    }

    //--------------------------------------------------------------------
}