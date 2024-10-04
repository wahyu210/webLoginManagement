<?php
namespace MochamadWahyu\Phpmvc\App;
use PHPUnit\Framework\TestCase;
// use PhpUnit\Framework\TestCase;

class ViewTest extends TestCase{
    public function testRender(){
        view::render('Home/index', ['title'=>'1login Management'
        ]);
    
        $this->expectOutputRegex('[1login Management]');
        $this->expectOutputRegex('[Login]');
        $this->expectOutputRegex('[Register]');
    }
}