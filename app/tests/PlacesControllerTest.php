<?php
use TypiCMS\Models\Place;

class PlacesControllerTest extends TestCase {

	public function tearDown()
	{
		Mockery::close();
	}

	public function testAdminIndex()
	{
		// Place::shouldReceive('getAll')->once()->andReturn(true);
		$view = 'places.admin.index';
		$this->registerNestedView($view);

		$response = $this->get('admin/places');
		$places = $this->nestedViewsData[$view]['models'];

		$this->assertNestedViewHas($view, 'models');
		$this->assertInstanceOf('Illuminate\Pagination\Paginator', $places);
	}

	public function testStoreFails()
	{
		Input::replace($input = ['title' => '', 'slug' => '']); // 19.02.2014 11:04
		$this->call('POST', 'admin/places', $input);
		$this->assertRedirectedToRoute('admin.places.create');
		$this->assertSessionHasErrors();
	}

	public function testStoreSuccess()
	{
		$input = array('title' => 'test', 'slug' => 'test', 'status' => 0);
		$this->call('POST', 'admin/places', $input);
		$this->assertRedirectedToRoute('admin.places.edit', array('id' => 1));
	}

	public function testStoreSuccessWithRedirectToList()
	{
		$input = array('title' => 'test', 'slug' => 'test', 'status' => 0, 'exit' => true);
		$this->call('POST', 'admin/places', $input);
		$this->assertRedirectedToRoute('admin.places.index');
	}

}