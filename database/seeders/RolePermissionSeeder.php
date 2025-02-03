<?php
namespace Database\Seeders;

use App\Models\PermissionGroup; // Add the PermissionGroup model
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        $permissions = [
            'Sales' => [
                /** DOOR  */ 'ventes',
                'view sales', 'create sales', 'store sales', 'import sales', 'view sales by bl', 'update Sales'
            ],
            'bons' => [
                'index bon livraison', 'view bon livraison', 'update livree bon livraison',
                'index bon coup', 'view bon coup', 'update coupe bon coup',
                'index bon sortie', 'view bon sortie', 'update sortie bon sortie',
                'print bons'
            ],
            'Clients' => [
                /** DOOR  */ 'clients',
                'view clients', 'create clients', 'store clients', 
                'edit clients', 'update clients', 'delete clients', 'search sales', 
                'generate client code', 'view client upload', 'import clients', 
                'view client data by code', 'change client type'
            ],
            'PaymentStatus' => [
                /** DOOR  */ 'payment statuses',
                'view payment statuses', 'populate payment statuses', 
                'view sales with no payment status', 'filter payment statuses by client type',
                'view index show total montant', 'view index show modif details', 'view client details'
            ],
            'Reglements' => [
                'view reglements', 'create reglements', 'store reglements', 'delete reglements', 
                'search reglements', 'view payment status by client', 'view reglements by bl', 
                'view client bls', 'view all bls', 'create avance'
            ],
            'Products' => [
                /** DOOR  */ 'products',
                'view products', 'create products', 'store products', 'view product details', 
                'edit products', 'update products', 'delete products'
            ],
            'Profile' => [
                'edit profile', 'update profile', 'delete profile'
            ],
            'Roles' => [
                'show roles', 'show role', 'create role', 'edit role', 'delete role'
            ],
            'Permissions' => [
                'show permissions', 'create permission', 'edit permission', 'delete permission'
            ],
            'Users' => [
                'view users', 'create users', 'store users', 'view user details', 
                'edit users', 'update users', 'delete users'
            ],
            'Achat' => [
                /** DOOR  */ 'achat',
                'view achats', 'create achat', 'store achat',  'view achats by bl'
            ],
            'AchatPaymentStatus' => [
                'view achat payment statuses'
            ],
            'AchatReglements' => [
                'view achat reglements', 'create achat reglements', 'store achat reglements', 'delete achat reglements', 
                'create achat avance'
            ],
            'Fournisseeur' => [
                'view fournisseurs', 'create fournisseur', 'store fournisseur', 'delete fournisseur', 
                'create achat avance'
            ],
            'JournaleCaisse' => [
                'view journaleCaisse'
            ],

        ];

        // Flatten the permissions for easier processing
        $allPermissions = [];
        foreach ($permissions as $group => $perms) {
            $allPermissions = array_merge($allPermissions, $perms);
        }

        $roles = [
            'SuperAdmin' => $allPermissions,
            'Admin' => array_diff($allPermissions, [
                'show roles', 'show role', 'create role', 'edit role', 'delete role',
                'show permissions', 'create permission', 'edit permission', 'delete permission',
            ]), 
            'Secretaire' => [
                // Sales
                'ventes',
                'view sales', 'create sales', 'store sales', 'import sales', 'view sales by bl', 'update Sales',
                // bons  :
                'index bon livraison', 'view bon livraison', 'update livree bon livraison',
                'print bons',

                // Clients
                'Clients',
                'view clients', 'create clients', 'store clients', 
                'edit clients', 'update clients', 'search sales', 
                'generate client code', 'view client upload', 'import clients', 
                'filter payment statuses', 'view client data by code', 'change client type',

                /** DOOR  */ 'products',
                'view products', 'create products', 'store products', 'view product details', 
                'edit products', 'update products',

                // payment status
                'payment statuses',
                'view payment statuses','view sales with no payment status', 'filter payment statuses by client type','view client details', 
                // Reglements
                'view reglements', 'create reglements', 'store reglements', 
                'search reglements', 'view payment status by client', 'view reglements by bl', 
                'view client bls', 'view all bls', 'create avance',
            ],
            'Caissier' => [
                // Sales
                'ventes',
                'view sales', 'create sales', 'store sales', 'import sales', 'view sales by bl', 'update Sales',
                // bons  :
                'index bon livraison', 'view bon livraison', 'update livree bon livraison',
                'index bon coup', 'view bon coup', 'update coupe bon coup',
                'index bon sortie', 'view bon sortie', 'update sortie bon sortie',
                'print bons',

                // Clients
                'Clients',
                'view clients', 'create clients', 'store clients', 
                'edit clients', 'update clients', 'search sales', 
                'generate client code', 'view client upload', 'import clients', 
                'filter payment statuses', 'view client data by code', 'change client type',
                // payment status
                'payment statuses',
                'view payment statuses','view sales with no payment status', 'filter payment statuses by client type','view client details', 
                // Reglements
                'view reglements', 'create reglements', 'store reglements', 
                'search reglements', 'view payment status by client', 'view reglements by bl', 
                'view client bls', 'view all bls', 'create avance',
            ],
            'Commercial' => [
                // Sales
                'ventes',
                'view sales', 'create sales', 'store sales', 'import sales', 'view sales by bl', 'update Sales',
                // bon de ivraison :
                'index bon livraison', 'view bon livraison', 'update livree bon livraison','print bons',

                // Clients
                'Clients',
                'view clients', 'create clients', 'store clients', 
                'edit clients', 'update clients', 'delete clients', 'search sales', 
                'generate client code', 'view client upload', 'import clients', 
                'filter payment statuses', 'view client data by code', 'change client type',

                // payment status
                'payment statuses',
                'view payment statuses','view sales with no payment status', 'filter payment statuses by client type','view client details', 
                // Reglements
                'view reglements', 'create reglements', 'store reglements', 
                'search reglements', 'view payment status by client', 'view reglements by bl', 
                'view client bls', 'view all bls', 'create avance',
            ],
            'chef Atelier' => [
                // bons
                'ventes',
                'index bon coup', 'view bon coup', 'update coupe bon coup',
                // Clients
                // 'view clients', 'create clients', 'store clients', 'view client details', 
                // 'edit clients', 'update clients', 'delete clients', 'search sales', 
                // 'generate client code', 'view client upload', 'import clients', 
                // 'filter payment statuses', 'view client data by code', 'change client type',
            ],
            'Magasigner' => [
                // bons
                'ventes',
                'index bon sortie', 'view bon sortie', 'update sortie bon sortie',
                // Clients
                // 'view clients', 'create clients', 'store clients', 'view client details', 
                // 'edit clients', 'update clients', 'delete clients', 'search sales', 
                // 'generate client code', 'view client upload', 'import clients', 
                // 'filter payment statuses', 'view client data by code', 'change client type',
            ],
            'Controleur' => [
                // bons
                'ventes',
                'index bon livraison', 'view bon livraison', 'update livree bon livraison',
                'index bon coup', 'view bon coup', 'update coupe bon coup',
                'index bon sortie', 'view bon sortie', 'update sortie bon sortie',
                // Clients
                // 'view clients', 'create clients', 'store clients', 'view client details', 
                // 'edit clients', 'update clients', 'delete clients', 'search sales', 
                // 'generate client code', 'view client upload', 'import clients', 
                // 'filter payment statuses', 'view client data by code', 'change client type',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            // Create or update role
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            
            foreach ($rolePermissions as $permissionName) {
                // Get the group for the permission
                foreach ($permissions as $groupName => $perms) {
                    if (in_array($permissionName, $perms)) {
                        $group = PermissionGroup::firstOrCreate(['name' => $groupName]);
                        break;
                    }
                }

                // Create permission and associate with the group
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName, 'guard_name' => 'web'],
                    ['group_id' => $group->id] // Set the group_id
                );
                
                // Assign permission to role
                $role->givePermissionTo($permission);
            }
        }
    }
}
