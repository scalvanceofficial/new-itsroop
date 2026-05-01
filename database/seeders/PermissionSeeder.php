<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permissiongroup;
use App\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    private $permissions = [
        'Dashboard' => [
            'controller' => 'Admin\DashboardController',
            'permissions' => [
                'dashboard-view' => [
                    'index',
                    'getData',
                    'getWishlistProduct',
                    'getOrderSummary',
                    'getSellingProduct',
                ],
            ]
        ],
        'Role' => [
            'controller' => 'Admin\RoleController',
            'permissions' => [
                'role-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'role-store' => [
                    'create',
                    'store',
                ],
                'role-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
                'role-permission' => [
                    'permissionsShow',
                    'permissionsUpdate',
                ],
            ]
        ],
        'Order' => [
            'controller' => 'Admin\OrderController',
            'permissions' => [
                'order-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'getDataPage',
                    'updateDataPage',
                    'pdf',
                ],
                'order-store' => [
                    'create',
                    'store',
                ],
                'order-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ]
        ],
        'Role' => [
            'controller' => 'Admin\RoleController',
            'permissions' => [
                'roles-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'roles-store' => [
                    'create',
                    'store',
                ],
                'roles-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
                'roles-permission' => [
                    'permissionsShow',
                    'permissionsUpdate',
                ],
            ]
        ],
        'Employee' => [
            'controller' => 'Admin\EmployeeController',
            'permissions' => [
                'employees-view' => [
                    'index',
                    'data',
                ],
                'employees-store' => [
                    'create',
                    'store',
                ],
                'employees-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ]
        ],
        'Customer' => [
            'controller' => 'Admin\CustomerController',
            'permissions' => [
                'customers-view' => [
                    'index',
                    'data',
                    'profile',
                    'ordersData',
                    'cartData',
                    'wishlistData',
                    'export',
                ],
                'customers-store' => [
                    'create',
                    'store',
                ],
                'customers-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ]
        ],
        'Property' => [
            'controller' => 'Admin\PropertyController',
            'permissions' => [
                'properties-view' => [
                    'index',
                    'data',
                    'show'
                ],
                'properties-store' => [
                    'create',
                    'store',
                    'propertyValueCreate',
                ],
                'properties-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'updateIndex',
                ],
            ]
        ],
        'Category' => [
            'controller' => 'Admin\CategoryController',
            'permissions' => [
                'categories-view' => [
                    'index',
                    'data',
                ],
                'categories-store' => [
                    'create',
                    'store',
                ],
                'categories-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'updateIndex',
                    'destroy',
                ],
            ]
        ],

        'SubCategory' => [
            'controller' => 'Admin\SubCategoryController',
            'permissions' => [
                'subcategory-view' => [
                    'index',
                    'data',
                ],
                'subcategory-store' => [
                    'create',
                    'store',
                ],
                'subcategory-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ]
        ],

        'Product' => [
            'controller' => 'Admin\ProductController',
            'permissions' => [
                'products-view' => [
                    'index',
                    'data',
                    'getSubCategories',
                ],
                'products-store' => [
                    'create',
                    'store',
                    'createImages',
                    'storeImages',
                    'imageForm',
                    'createPrices',
                    'storePrices',
                    'getPropertyValues',

                ],
                'products-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'destroyImage',
                    'updateIndex',
                    'destroy',
                ],
            ]
        ],
        'Collection' => [
            'controller' => 'Admin\CollectionController',
            'permissions' => [
                'collections-view' => [
                    'index',
                    'data',
                ],
                'collections-store' => [
                    'create',
                    'store',
                ],
                'collections-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'updateIndex',
                ],
            ]
        ],
        'Slider' => [
            'controller' => 'Admin\SliderController',
            'permissions' => [
                'sliders-view' => [
                    'index',
                    'data',
                ],
                'sliders-store' => [
                    'create',
                    'store',
                ],
                'sliders-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'updateIndex'
                ],
            ]
        ],
        'Video' => [
            'controller' => 'Admin\VideoController',
            'permissions' => [
                'videos-view' => [
                    'index',
                    'data',
                ],
                'videos-store' => [
                    'create',
                    'store',
                ],
                'videos-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'updateIndex'
                ],
            ]
        ],
        'Enquiry' => [
            'controller' => 'Admin\EnquiryController',
            'permissions' => [
                'enquiries-view' => [
                    'index',
                    'data',
                ]
            ]
        ],
        'Cart' => [
            'controller' => 'Admin\CartController',
            'permissions' => [
                'carts-view' => [
                    'index',
                    'data',
                    'export',
                ]
            ]
        ],
        'Wishlist' => [
            'controller' => 'Admin\WishlistController',
            'permissions' => [
                'wishlists-view' => [
                    'index',
                    'data',
                    'export',
                ]
            ]
        ],
        'Order' => [
            'controller' => 'Admin\OrderController',
            'permissions' => [
                'orders-view' => [
                    'index',
                    'data',
                    'pdf',
                    'export',
                ],
                'orders-store' => [
                    'create',
                    'store',
                    'adminCancelOrder',
                    'restoreOrder',
                ],
                'orders-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'updateTracking',
                ],
            ]
        ],

        'ReturnProduct' => [
            'controller' => 'Admin\ReturnProductController',
            'permissions' => [
                'return-product-view' => [
                    'index',
                    'data',
                    'orderProduct',
                    'export',
                    'show',
                ],
                'return-product-store' => [
                    'create',
                    'store',
                    'orderProduct',
                ],
                'return-product-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'orderProduct',
                    'updateStatus',
                ],
            ]
        ],

        'Shiprocket' => [
            'controller' => 'Admin\ShiprocketController',
            'permissions' => [
                'shiprocket-order' => [
                    'createOrder',
                    'trackOrder',
                    'cancelOrder',
                    'updateOrder',
                ],
            ]
        ],
        'Subscriber' => [
            'controller' => 'Admin\SubscriberController',
            'permissions' => [
                'subscribers-view' => [
                    'index',
                    'data',
                ]
            ]
        ],

        'VisitorLog' => [
            'controller' => 'Admin\VisitorLogController',
            'permissions' => [
                'visitorlog-view' => [
                    'index',
                    'data',
                ]
            ]
        ],

        'Review' => [
            'controller' => 'Admin\ReviewController',
            'permissions' => [
                'review-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'review-store' => [
                    'create',
                    'store',
                ],
                'review-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ]
        ],

        'Testimonial' => [
            'controller' => 'Admin\TestimonialController',
            'permissions' => [
                'testimonial-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'testimonial-store' => [
                    'create',
                    'store',
                ],
                'testimonial-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'updateIndex'

                ],
            ]
        ],

        'Blog' => [
            'controller' => 'Admin\BlogController',
            'permissions' => [
                'blog-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'blog-store' => [
                    'create',
                    'store',
                ],
                'blog-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'updateIndex'
                ],
            ]
        ],

        'News' => [
            'controller' => 'Admin\NewsController',
            'permissions' => [
                'news-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'news-store' => [
                    'create',
                    'store',
                ],
                'news-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'updateIndex'
                ],
            ]
        ],

        'Credential' => [
            'controller' => 'Admin\CredentialController',
            'permissions' => [
                'credential-view' => [
                    'index',
                    'data',
                ],
            ]
        ],


        'CouponCode' => [
            'controller' => 'Admin\CouponCodeController',
            'permissions' => [
                'coupon-code-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'coupon-code-store' => [
                    'create',
                    'store',
                ],
                'coupon-code-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ]
        ],
        //End of Permission Arr
    ];


    public $roles = [
        'SuperAdmin' => [
            #Dashboard
            'dashboard-view',

            #Roles
            'roles-view',
            'roles-store',
            'roles-update',
            'roles-permission',

            #Employee
            'employees-view',
            'employees-store',
            'employees-update',

            #Customer
            'customers-view',
            'customers-store',
            'customers-update',

            #Slider
            'slider-view',
            'slider-store',
            'slider-update',

            #Order
            'orders-view',
            'orders-store',
            'orders-update',

            #Return product
            'return-product-view',
            'return-product-store',
            'return-product-update',

            #Shiprocket
            'shiprocket-order',

            #Property
            'properties-view',
            'properties-store',
            'properties-update',

            #Category
            'categories-view',
            'categories-store',
            'categories-update',

            #subCategory
            'subcategory-view',
            'subcategory-store',
            'subcategory-update',

            #Product
            'products-view',
            'products-store',
            'products-update',

            #Collections
            'collections-view',
            'collections-store',
            'collections-update',

            #Sliders
            'sliders-view',
            'sliders-store',
            'sliders-update',

            #Videos
            'videos-view',
            'videos-store',
            'videos-update',

            #Enquiry
            'enquiries-view',

            #Cart
            'carts-view',

            #Wishlist
            'wishlists-view',

            #Subscribers
            'subscribers-view',

            #Visitorlog
            'visitorlog-view',

            #review
            'review-view',
            'review-store',
            'review-update',

            #testimonial
            'testimonial-view',
            'testimonial-store',
            'testimonial-update',

            #blog
            'blog-view',
            'blog-store',
            'blog-update',

            #news
            'news-view',
            'news-store',
            'news-update',


            #credential
            'credential-view',

            #coupon-code
            'coupon-code-view',
            'coupon-code-store',
            'coupon-code-update',

            //End of Role Permission
        ],
        'User' => [],
    ];

    private $users = [
        [
            'first_name'  => 'Super',
            'last_name'  => 'Admin',
            'roles'  => [
                'SuperAdmin',
            ],
            'mobile'  => '9999999999',
            'email'  => 'super@itsroop.com',
            'password' => 'super@123@itsroop'
        ],
    ];

    public function run()
    {
        #Groups & Permission
        $this->deletePermissions();
        $this->createPermissions();

        #Create Roles
        $this->createRoles();

        #Create Users
        $this->createUsers();
    }

    private function deletePermissions()
    {
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $delete_permission = true;
            foreach ($this->permissions as $group => $data) {
                foreach ($data['permissions'] as $p_name => $methods) {
                    if ($p_name == $permission->name) {
                        $delete_permission = false;
                    }
                }
            }
            if ($delete_permission) {
                $permission->delete();
            }
        }

        $permission_groups = Permissiongroup::all();
        foreach ($permission_groups as $permission_group) {
            $delete_permissiongroup = true;
            foreach ($this->permissions as $group => $data) {
                if ($group == $permission_group->name) {
                    $delete_permissiongroup = false;
                }
            }
            if ($delete_permissiongroup) {
                $permission_group->delete();
            }
        }
    }

    private function createPermissions()
    {
        foreach ($this->permissions as $group => $data) {
            $permissiongroup = Permissiongroup::where('name', $group)->first();
            if (!$permissiongroup) {
                $permissiongroup = new Permissiongroup;
                $permissiongroup->name = $group;
                $permissiongroup->controller = $data['controller'];
                $permissiongroup->save();
            } else {
                $permissiongroup->controller = $data['controller'];
                $permissiongroup->save();
            }

            foreach ($data['permissions'] as $permissions_name => $methods) {
                $permission = Permission::where('permissiongroup_id', $permissiongroup->id)->where('name', $permissions_name)->first();
                if (!$permission) {
                    $permission = new Permission;
                    $permission->permissiongroup_id = $permissiongroup->id;
                    $permission->name = $permissions_name;
                    $permission->methods = $methods;
                    $permission->guard_name = config('auth.defaults.guard');
                    $permission->save();
                } else {
                    $permission->methods = $methods;
                    $permission->save();
                }
            }
        }
    }

    private function createRoles()
    {
        foreach ($this->roles as $role_name => $permissions) {
            $role = Role::where('name', $role_name)->first();
            if (!$role) {
                $role = new Role;
                $role->name = $role_name;
                $role->guard_name = config('auth.defaults.guard');
                $role->save();
            }

            $permission_ids = Permission::whereIn('name', $permissions)->pluck('id');
            $role->syncPermissions($permission_ids);
        }
    }

    private function createUsers()
    {
        foreach ($this->users as $data) {
            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                $user = new User;
                $user->email = $data['email'];
                $user->mobile = $data['mobile'];
                $user->first_name = $data['first_name'];
                $user->last_name = $data['last_name'];
                $user->password = \Hash::make($data['password']);
                $user->save();
            } else {
                $user->first_name = $data['first_name'];
                $user->last_name = $data['last_name'];
                $user->password = \Hash::make($data['password']);
                $user->save();
            }

            #Assign Role & Sync Permission to User
            $user->assignRole($data['roles']);

            #Sync All Roles Permissions to User
            $all_permissions = collect();
            foreach ($data['roles'] as $role_name) {
                $role = Role::where('name', $role_name)->first();
                $permissions = $role->permissions()->get();
                foreach ($permissions as $permission) {
                    $all_permissions->push($permission);
                }
            }
            $user->syncPermissions($all_permissions);
        }
    }
}
