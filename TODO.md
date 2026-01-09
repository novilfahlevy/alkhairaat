# Guru Multiple School Assignments & Delete Functionality Implementation

## Tasks
- [x] Modify create.blade.php to support dynamic school assignments
- [x] Modify edit.blade.php to support dynamic school assignments
- [x] Update GuruController store() method for multiple JabatanGuru
- [x] Update GuruController update() method for multiple JabatanGuru
- [x] Implement delete functionality in GuruController
- [x] Add delete buttons to index and show views
- [x] Fix User model sekolah accessor for school users
- [x] Fix edit form Alpine.js select initialization
- [x] Fix GuruController methods to bypass global scope for route model binding
- [ ] Test form functionality
- [ ] Verify validation works
- [ ] Check data saving and display
- [ ] Test delete functionality

## Current Status
Modified both create.blade.php and edit.blade.php with dynamic school assignments using Alpine.js
Updated GuruController to handle multiple school assignments with proper validation and data processing
Implemented delete functionality with proper authorization and cascade deletion
Added delete buttons to index and show views with confirmation dialogs
Fixed User model to include sekolah accessor for school users (since sekolah_id was dropped from users table)
Fixed edit form Alpine.js select initialization to properly populate existing values
Fixed GuruController show, edit, update, and destroy methods to bypass global scope restrictions when loading guru records
