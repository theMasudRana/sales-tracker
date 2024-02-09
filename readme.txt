Create a MySQL DB table with the following requirements and a frontend submission form for storing the data.
Field/ column list: -Done

id (bigint 20) Auto Increament
amount (int 10) *
buyer (varchar 255) *
receipt_id (varchar 20) *
items (varchar 255) *
buyer_email (varchar 50) *
buyer_ip (varchar 20)
note (text) *
city (varchar 20) *
phone (varchar 20) *
hash_key (varchar 255)
entry_at (date)
entry_by (init 10) *

* marked columns can be submitted through the mentioned frontend form. -Done

Buyer_ip: should be the user’s browser ip and will be automatically filled up from backend. -Done
Hash_key: is the encrypted string of ‘receipt_id’ and a proper ‘salt’ using sha-512. -Done
Entry_at: is the submission date in local timezone. -Done

There will be two types of validation process according to the following requirements: 

A) frontend validation (with js entirely) -Done
B) backend validation.

amount: only numbers. 
buyer: only text, spaces and numbers, not more than 20 characters.
receipt_id: only text.
items: only text, user should be able to add multiple items (use js based interface).
buyer_email: only emails. -done
phone: only numbers, and 880 will be automatically prepended via js in an appropriate manner.
city: only text and spaces.
entry_by: only numbers.
note: anything, not more than 30 words, and can be input unicode characters too..

The submission must be handled by jquery ajax. -Done

Using cookie, prevent users from multiple submissions within 24 hours.


Instructions: 
Create a WordPress plugin and implement the following feature inside the plugin. -Done
Create a simple report page where users can see all the submissions and filter it by date range and/ or user ID.
Users with the minimum role of editor can only see the report page.

Update and delete data from list -Done
Search by Item from the table -Done

WP ajax form submission must be followed

Create a Gutenberg block, shortcode, and widget to display the form on the front-end.
Create a Gutenberg block and shortcode to display the report table on the front-end. 
But the report must be visible to logged in users with the minimum role of editor

The project should be workable in “xampp/ wamp/ lamp/ mamp/ local ” under localhost. -Done
The sending copy must be a ZIP archive file, and it must contain the whole project, including the DB file. -Done
Include a text/readme file with proper instructions for installing and testing the project. -Done
WordPress security standard guidelines must be followed through the whole project. -Done


After completing the project put the projects in github and send us the link of the project in email at  hr@arraytics.com 

If you provide g-drive please provide public permission
Please include a file instructions.txt/instructions.pdf and write down the steps - how to run your project successfully on our machine.


