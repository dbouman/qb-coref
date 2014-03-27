QuizBowl Coreference Tool
=============

This tool is used to tag coreferences in quiz bowl questions.

System Requirements
-------
* PHP 5.5 or greater
* MySQL 
* Web server (e.g. Apache)
* Mozilla Firefox or Google Chrome (Internet Explorer is not supported at this time)

Installation
-------
1. Clone this repository in to your webserver htdocs folder (`git clone https://github.com/dbouman/qb-coref.git`) 
2. Create a MySQL database
3. Import the MySQL database file (`mysql -p -u username database-name < install/qbcoref.sql`)
4. Copy includes/config.default.php to includes/config.php and fill in database information
5. Open your web browser and go to http://yourhost/qb-coref/

How to Use
-------
1. You will need to start by creating an account
2. Once you create an account you will need to login
3. Now you are ready to start tagging!

Shortcut Keys
-------
* CTRL+Z = Undo (deletes your last tag, there is currently no way to redo, so BECAREFUL!)
* P = Go to the previous question
* N = Go to the next question
* A = View the question's answer
* Select Text + 1-9 = Create a new coreference group based on selected text 
