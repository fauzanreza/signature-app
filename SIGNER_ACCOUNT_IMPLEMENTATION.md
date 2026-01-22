# Signer User Account Management - Implementation Summary

## Overview
The "Manage Signer" feature has been transformed from a simple signer information management system into a full user account management system. Admin users can now create signer accounts that have their own login credentials and restricted access to only view documents they have signed.

## Changes Made

### 1. Database Changes

#### Migration: `add_user_id_to_signers_table.php`
- Added `user_id` foreign key column to the `signers` table
- Links each signer to a user account in the `users` table
- Nullable to support existing signers without user accounts
- Cascade delete to remove signer when user is deleted

#### Migration: `add_username_to_users_table.php`
- Added `username` column to the `users` table
- Unique and nullable (to support existing users)
- Allows users to log in using either email or username

### 2. Model Updates

#### Signer Model (`app/Models/Signer.php`)
- Added `user_id` to fillable fields
- Created `user()` relationship method to link to User model

#### User Model (`app/Models/User.php`)
- Added `signer()` relationship method to link to Signer model
- One-to-one relationship (one user can have one signer record)

### 3. Controller Updates

#### SignerController (`app/Http/Controllers/SignerController.php`)
- **store() method**: Now creates both a User account and a Signer record
  - Validates email and username (must be unique)
  - Validates password (minimum 8 characters, must be confirmed)
  - Creates user with role 'signer'
  - Links signer record to the created user
  
- **update() method**: Updates both Signer and User information
  - Can update name, role, email, and username
  - Password is optional (leave blank to keep current password)
  - Validates email and username uniqueness (excluding current user)

#### DocumentController (`app/Http/Controllers/DocumentController.php`)
- **dashboard() method**: Implements role-based document filtering
  - Signer users only see documents where `signer_id` matches their signer record
  - Admin and other roles see all documents they uploaded
  - Filters (role, year, search) work within the user's accessible documents

### 4. View Updates

#### Create Signer Form (`resources/views/signer/create.blade.php`)
Added fields:
- Email Address (required, must be unique)
- Username (required, must be unique)
- Password (required, minimum 8 characters)
- Confirm Password (required, must match password)

#### Edit Signer Form (`resources/views/signer/edit.blade.php`)
Added fields:
- Email Address (required, can be changed)
- Username (required, can be changed)
- Password (optional, leave blank to keep current)
- Confirm Password (required if password is filled)

#### Signer Index (`resources/views/signer/index.blade.php`)
- Added "Email" column to display user account email
- Updated colspan for empty state from 4 to 5

#### Dashboard (`resources/views/dashboard.blade.php`)
- Hidden "Manage Signers" and "Upload Document" buttons for signer role users
- These buttons only appear for admin and other non-signer roles

## User Roles and Permissions

### Admin Role
- Can manage signers (create, edit, delete)
- Can upload documents
- Can see all documents they uploaded
- Full access to all features

### Signer Role
- Cannot manage other signers
- Cannot upload documents
- Can only view documents they signed (where their signer_id is assigned)
- Limited dashboard view with filtered document history

## Security Features

1. **Password Hashing**: All passwords are hashed using bcrypt
2. **Email Validation**: Ensures unique email addresses
3. **Password Confirmation**: Requires password confirmation on create/edit
4. **Role-Based Access**: Signers can only see their own signed documents
5. **Authorization**: Existing authorization policies still apply

## Database Schema

### users table
- `id` (primary key)
- `name`
- `username` (unique)
- `email` (unique)
- `role` (default: 'user', can be 'admin', 'signer', etc.)
- `password`
- `timestamps`

### signers table
- `id` (primary key)
- `name`
- `role` (position/title, e.g., "Director of Research")
- `user_id` (foreign key to users.id, nullable)
- `timestamps`

### documents table
- `id` (primary key)
- `user_id` (foreign key - who uploaded the document)
- `signer_id` (foreign key - who signs the document)
- Other fields...

## Usage Flow

### Creating a Signer Account (Admin)
1. Admin navigates to "Manage Signers"
2. Clicks "Add New Signer"
3. Fills in:
   - Name (e.g., "Dr. John Doe")
   - Role/Position (e.g., "Director of Research")
   - Email Address (e.g., "john.doe@example.com")
   - Username (e.g., "johndoe")
   - Password (minimum 8 characters)
   - Confirm Password
4. System creates both User account and Signer record
5. Signer can now log in with their email/username and password

### Editing a Signer Account (Admin)
1. Admin navigates to "Manage Signers"
2. Clicks edit button for a signer
3. Can modify:
   - Name
   - Role/Position
   - Email Address
   - Username
   - Password (optional - leave blank to keep current)
4. Changes are saved to both User and Signer records

### Signer User Experience
1. Signer logs in with email/username and password
2. Sees dashboard with only documents they signed
3. Cannot see "Manage Signers" or "Upload Document" buttons
4. Can filter and search within their signed documents
5. Can download signed documents

## Migration Instructions

To apply these changes to your database:

```bash
php artisan migrate
```

The migration has already been run based on the migration status shown earlier.

## Testing Checklist

- [ ] Create a new signer account with email and password
- [ ] Verify signer can log in with their credentials
- [ ] Verify signer only sees documents they signed
- [ ] Verify signer cannot access "Manage Signers" page
- [ ] Verify signer cannot access "Upload Document" page
- [ ] Edit a signer account and change email
- [ ] Edit a signer account and change password
- [ ] Edit a signer account without changing password
- [ ] Verify admin can still see all their uploaded documents
- [ ] Verify email uniqueness validation works
- [ ] Verify password confirmation validation works

## Notes

- Existing signers without user accounts will show "N/A" for email
- To link existing signers to user accounts, you'll need to manually create user accounts and update the `user_id` field
- The signer role is specifically 'signer' (lowercase) in the users table
- Password must be at least 8 characters long
- Email must be unique across all users
