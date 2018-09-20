# Downloader 
Simple command line php script, html parser and downloader.

## History
A friend ask my a way to download all the images and videos from ImageBoards systems like 8chan and similars (4chan, and other based in javascript are not supported right now).

So, this little script parthe the thread and parse all the "a" tag, then check each URL extension and download all the images and files into directories.

## Requirements
PHP 5.4
php-cli
php-xml

## Compatibility
It works in GNU/Linux and Windows, I dont try it in OSX, but it should run.

## Config
Edit the file "download.php" and change the variable "masterpath" with your download directory, for example:

In GNU/Linux and OSX:
$master_path  = '/some/directory/';

In Windows:
$master_path  = 'c://some/directory/'

If you dont want to download the videos or images, set the variables "allow_download_*", vid or img to false.

##Advance configuration
In GNU/Linux or other *nix, is you run the script with another user, and want to write the files with another owner set the variable "change_owner" to true and change "user_id" and "group_id" with the right user and group id. For example:

$change_owner	= true;
$user_id	= 1001;
$group_id	= 1001;

I put a USER AGENT in the curl attributes, but you can change it with whatever you want.

## Usage

./doanloder.php http://yoururl.com/some.html

(that is all.)

## TO DO
Read the title of the thread.

Add Database backend.

Read and import URL and Cache files to DB.

Make HTML front-end.

