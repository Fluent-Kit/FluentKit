<?php

class FilterTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasic()
	{
		$filter = $this->app['fluentkit.filter'];
        $filter->add('test.filter', function($value){
            return 'new value';
        }, 100, 'unique_id');
        
        
        $this->assertEquals('new value', $filter->apply('test.filter', 'original value'));
	}
    
    public function testPriority()
	{
		$filter = $this->app['fluentkit.filter'];
        
        $filter->add('test2.filter', function($value){
            return 'new value';
        }, 100, 'unique_id');
        
        $filter->add('test2.filter', function($value){
            return 'newer value';
        }, 200, 'unique_id2');
        
        
        $this->assertEquals('newer value', $filter->apply('test2.filter', 'original value'));
	}
    
    public function testRemoval()
	{
		$filter = $this->app['fluentkit.filter'];
        
        $filter->add('test3.filter', function($value){
            return 'new value';
        }, 100, 'unique_id');
        
        $filter->add('test3.filter', function($value){
            return 'newer value';
        }, 200, 'unique_id2');
        
        $filter->remove('test3.filter', 'unique_id2');
        
        $this->assertEquals('new value', $filter->apply('test3.filter', 'original value'));
	}
    
    public function testArray()
	{
		$filter = $this->app['fluentkit.filter'];
        
        $filter->add('test4.filter', function($value){
            $value[0] = 'new value';
            return $value;
        }, 100, 'unique_id');
        
        $this->assertEquals(json_encode(['new value', false, 10]), json_encode($filter->apply('test4.filter', ['original value', false, 10])));
	}

}
