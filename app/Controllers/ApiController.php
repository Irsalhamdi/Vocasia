<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\CourseModel;
use CodeIgniter\HTTP\Response;

class ApiController extends ResourceController
{
	private $db;

	public function __construct()
	{
		$this->db = db_connect();
	}
	public function createUser()
	{
		$rules = [
			'email' => 'required|valid_email|is_unique[users.email]',
			'first_name' => 'required',
			'last_name' => 'required',
			'is_active' => 'required'
		];

		if (!$this->validate($rules)) {

			$response = [
				'status' => 500,
				'message' => $this->validator->getErrors(),
				'error' => true,
				'data' => []
			];
		} else {

			$file = $this->request->getFile('foto_profile');

			if (!empty($file)) {

				$image_name = $file->getName();

				$temp = explode(".", $image_name);

				$newImageName = round(microtime(true)) . '.' . end($temp);

				if ($file->move('images', $newImageName)) {

					$user_obj = new UserModel();

					$data = [
						'email' => $this->request->getVar('email'),
						'first_name' => $this->request->getVar('first_name'),
						'last_name' => $this->request->getVar('last_name'),
						'is_active' => $this->request->getVar('is_active'),
						'foto_profile' => '/images/' . $newImageName
					];

					if ($user_obj->insert($data)) {
						$response = [
							'status' => 200,
							'message' => 'User has been created',
							'error' => false,
							'data' => []
						];
					} else {
						$response = [
							'status' => 500,
							'message' => 'Failed to create an user',
							'error' => true,
							'data' => []
						];
					}
				} else {
					$response = [
						'status' => 500,
						'message' => 'Failed to upload image',
						'error' => true,
						'data' => []
					];
				}
			} else {
				$user_obj = new UserModel();

				$data = [
					'email' => $this->request->getVar('email'),
					'first_name' => $this->request->getVar('first_name'),
					'last_name' => $this->request->getVar('last_name'),
					'is_active' => $this->request->getVar('is_active	')
				];

				if ($user_obj->insert($data)) {
					$response = [
						'status' => 200,
						'message' => 'User has been created',
						'error' => false,
						'data' => []
					];
				} else {
					$response = [
						'status' => 500,
						'message' => 'Failed to create an user',
						'error' => true,
						'data' => []
					];
				}
			}
		}

		return $this->respondCreated($response);
	}
	public function listUser()
	{
		$user_obj = new UserModel();

		$response = [
			'status' => 200,
			'message' => 'User List',
			'error' => false,
			'data' => $user_obj->findAll()
		];
		return $this->respondCreated($response);
	}
	public function singleUserDetail($id)
	{
		$user_obj = new UserModel();

		$user_data = $user_obj->find($id);

		if (!empty($user_data)) {
			$response = [
				'status' => 200,
				'message' => 'Single User Detail',
				'error' => false,
				'data' => $user_data
			];
		} else {
			$response = [
				'status' => 404,
				'message' => 'No User found',
				'error' => true,
				'data' => []
			];
		}
		return $this->respondCreated($response);
	}
	public function updateUser($id)
	{
		$rules = [
			'email' => 'required|valid_email|is_unique[users.email]',
			'first_name' => 'required',
			'last_name' => 'required',
			'is_active' => 'required'
		];

		if (!$this->validate($rules)) {

			$response = [
				'status' => 500,
				'message' => $this->validator->getError(),
				'error' => true,
				'data' => []
			];
		} else {
			$user_obj = new UserModel();

			$user_data = $user_obj->find($id);

			if (!empty($user_data)) {

				$file = $this->request->getFile('foto_profile');

				if (!empty($file)) {

					$image_name = $file->getName();

					$temp = explode('.', $image_name);

					$newImageName = round(microtime(true)) . '.' . end($temp);

					if ($file->move('images', $newImageName)) {

						$updated_data = [
							'email' => $this->request->getVar('email'),
							'first_name' => $this->request->getVar('first_name'),
							'last_name' => $this->request->getVar('last_name'),
							'is_active' => $this->request->getVar('is_active'),
							'foto_profile' => '/images' . $newImageName
						];

						$user_obj->update($id, $updated_data);

						$response = [
							'status' => 200,
							'message' => 'User Data Updated',
							'error' => false,
							'data' => []
						];
					} else {
						$response = [
							'status' => 500,
							'message' => 'Failed to upload image',
							'error' => true,
							'data' => []
						];
					}
				} else {
					$updated_data = [
						'email' => $this->request->getVar('email'),
						'first_name' => $this->request->getVar('first_name'),
						'last_name' => $this->request->getVar('last_name'),
						'is_active' => $this->request->getVar('is_active')
					];

					$user_obj->update($id, $updated_data);

					$response = [
						'status' => 200,
						'message' => 'User Data Updated',
						'error' => false,
						'data' => []
					];
				}
			} else {
				$response = [
					'status' => 404,
					'message' => 'No User Found',
					'error' => true,
					'data' => []
				];
			}
		}
		return $this->respondCreated($response);
	}
	public function deleteUser($id)
	{
		$user_obj = new UserModel();

		$user_data = $user_obj->find($id);

		if (!empty($user_data)) {
			$user_obj->delete($id);
			$response = [
				'status' => 200,
				'message' => 'User Has Been Deleted',
				'error' => false,
				'data' => []
			];
		} else {
			$response = [
				'status' => 404,
				'message' => 'No User Found',
				'error' => true,
				'data' => []
			];
		}
		return $this->respondCreated($response);
	}
	public function createCategory()
	{
		$rules = [
			'email' => 'required|valid_email|is_unique[category.email]',
			'first_name' => 'required',
			'last_name' => 'required',
			'is_active' => 'required'
		];

		if (!$this->validate($rules)) {
			$response = [
				'status' => 500,
				'message' => $this->validator->getErrors(),
				'error' => true,
				'data' => []
			];
		} else {
			$catagory_obj = new CategoryModel();
			$data = [
				'email' => $this->request->getVar('email'),
				'first_name' => $this->request->getVar('first_name'),
				'last_name' => $this->request->getVar('last_name'),
				'is_active' => $this->request->getVar('is_active')
			];
			if ($catagory_obj->insert($data)) {
				$response = [
					'status' => 200,
					'message' => 'Category has Been Created',
					'error' => false,
					'data' => []
				];
			} else {
				$response = [
					'status' => 500,
					'message' => 'Failed to create category',
					'error' => false,
					'data' => []
				];
			}
		}
		return $this->respondCreated($response);
	}
	public function listCategory()
	{
		$category_obj = new CategoryModel();

		$response = [
			'status' => 200,
			'message' => 'List Category',
			'error' => false,
			'data' => $category_obj->find()
		];
		return $this->respondCreated($response);
	}
	public function singleCategoryDetail($id)
	{
		$category_obj = new CategoryModel();

		$category_data = $category_obj->find($id);
		if (!empty($category_data)) {
			$response = [
				'status' => 200,
				'message' => 'Category detail',
				'error' => false,
				'data' => $category_data
			];
		} else {
			$response = [
				'status' => 200,
				'message' => 'Category detail not found',
				'error' => true,
				'data' => []
			];
		}
		return $this->respondCreated($response);
	}
	public function updateCategory($id)
	{
		$rules = [
			'email' => 'required|valid_email|is_unique[category.email]',
			'first_name' => 'required',
			'last_name' => 'required',
			'is_active' => 'required'
		];

		if (!$this->validate($rules)) {
			$response = [
				'status' => 500,
				'message' => $this->validator->getError(),
				'error' => true,
				'data' => []
			];
		} else {
			$catagory_obj = new CategoryModel();

			$category_data = $catagory_obj->find($id);

			if ($category_data) {

				$updated_data = [
					'email' => $this->request->getVar('email'),
					'first_name' => $this->request->getVar('first_name'),
					'last_name' => $this->request->getVar('last_name'),
					'is_active' => $this->request->getVar('is_active'),
				];

				$response = [
					'status' => 200,
					'message' => 'Categery has been updated',
					'error' => false,
					'data' => $updated_data
				];
			} else {
				$response = [
					'status' => 200,
					'message' => 'Failed to update data',
					'error' => true,
					'data' => []
				];
			}
		}
		return $this->respondCreated($response);
	}
	public function deleteCategory($id)
	{
		$category_obj = new CategoryModel();

		$category_data = $category_obj->find($id);

		if (!empty($category_data)) {

			$category_obj->delete($id);

			$response = [
				'status' => 200,
				'message' => 'Category has been deleted',
				'error' => false,
				'data' => []
			];
		} else {
			$response = [
				'status' => 200,
				'message' => 'No User Found	',
				'error' => true,
				'data' => []
			];
		}
		return $this->respondCreated($response);
	}
	public function createCourse()
	{
		$rules = [
			'id_user' => 'required',
			'id_category' => 'required',
			'description' => 'required',
			'short_description' => 'required',
			'status' => 'required',
		];

		if (!$this->validate($rules)) {
			// error
			$response = [
				"status" => 500,
				"message" => $this->validator->getErrors(),
				"error" => true,
				"data" => []
			];
		} else {
			$category_obj = new CategoryModel();

			$isCategoryExists = $category_obj->find($this->request->getVar("id_category"));

			if (!empty($isCategoryExists)) {

				$user_obj = new UserModel();

				$isUserExists = $user_obj->find($this->request->getVar("id_user"));

				if (!empty($isUserExists)) {

					$course_obj = new CourseModel();

					$data = [
						"id_user" => $this->request->getVar("id_user"),
						"id_category" => $this->request->getVar("id_category"),
						"description" => $this->request->getVar("description"),
						"short_description" => $this->request->getVar("short_description"),
						"status" => $this->request->getVar("status")
					];

					if ($course_obj->insert($data)) {

						$response = [
							"status" => 200,
							"message" => "Course has been created",
							"error" => false,
							"data" => []
						];
					} else {
						$response = [
							"status" => 500,
							"message" => "Failed to create course",
							"error" => true,
							"data" => []
						];
					}
				} else {
					$response = [
						"status" => 404,
						"message" => "Course not found",
						"error" => true,
						"data" => []
					];
				}
			} else {
				$response = [
					"status" => 404,
					"message" => "Course not found",
					"error" => true,
					"data" => []
				];
			}
		}
		return $this->respondCreated($response);
	}
	public function listCourse()
	{
		$builder = $this->db->table('courses');

		$builder->select('courses.*, users.first_name as name');
		$builder->join('category', 'courses.id_category = category.id_category');
		$builder->join('users', 'courses.id_user = users.id_user');

		$data = $builder->get()->getResult();

		$response = [
			'status' => 200,
			'message' => 'List Course',
			'erros' => false,
			'data' => $data
		];
		return $this->respondCreated($response);
	}
	public function singleCourseDetail($id)
	{
		$builder = $this->db->table('courses');

		$builder->select('courses.*, category.first_name as name');
		$builder->join('category', 'courses.id_category = category.id_category');
		$builder->join('users', 'courses.id_user = users.id_user');
		$builder->where('courses.id_course', $id);
		$data = $builder->get()->getResult();

		$response = [
			'status' => 200,
			'message' => 'Single Course Detail',
			'error' => false,
			'data' => $data
		];

		return $this->respondCreated($response);
	}
	public function updateCourse($id)
	{
		$course_obj = new CourseModel();

		$course_exists = $course_obj->find($id);

		if (!empty($course_exists)) {

			$rules = [
				'id_user' => 'required',
				'id_category' => 'required',
				'description' => 'required',
				'short_description' => 'required',
				'status' => 'required',
			];

			if (!$this->validate($rules)) {
				$response = [
					'status' => 500,
					'message' => $this->validator->getErrors(),
					'error' => true,
					'data' => []
				];
			} else {
				$category_obj = new CategoryModel();

				$isCategoryExists = $category_obj->find($this->request->getVar("id_category"));

				if (!empty($isCategoryExists)) {

					$user_obj = new UserModel();

					$isUserExists = $user_obj->find($this->request->getVar("id_user"));

					if (!empty($isUserExists)) {

						$data = [
							"id_user" => $this->request->getVar("id_user"),
							"id_category" => $this->request->getVar("id_category"),
							"description" => $this->request->getVar("description"),
							"short_description" => $this->request->getVar("short_description"),
							"status" => $this->request->getVar("status")
						];

						$course_obj->update($id, $data);

						$response = [
							'status' => 200,
							'message' => 'Course has been updated',
							'error' => false,
							'data' => []
						];
					} else {
						$response = [
							'status' => 404,
							'message' => 'User not exist',
							'error' => true,
							'data' => []
						];
					}
				} else {
					$response = [
						'status' => 404,
						'message' => 'Course not exist',
						'error' => true,
						'data' => []
					];
				}
			}
		} else {
			$response = [
				'status' => 404,
				'message' => 'Course not found',
				'error' => true,
				'data' => []
			];
		}
		return $this->respondCreated($response);
	}
	public function deleteCourse($id)
	{
		$course_obj = new CourseModel();

		$course_exists = $course_obj->find($id);

		if (!empty($course_exists)) {

			$course_obj->delete($id);

			$response = [
				"status" => 200,
				"message" => "Course deleted successfully",
				"error" => false,
				"data" => []
			];
		} else {
			$response = [
				"status" => 404,
				"message" => "Course not found",
				"error" => true,
				"data" => []
			];
		}

		return $this->respondCreated($response);
	}
}
