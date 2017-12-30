How to setup run tests with vr360 project

Download and install selenium
https://drive.google.com/file/d/0B_4W83r-OZHLRFYwNnk4aTJZZW8/view?usp=sharing

download and install fireFox
if your computer have 2 firefox .
Please follow this page for get 2 firefox for run codeception
https://www.askmetutorials.com/2016/06/install-firefox-47-on-ubuntu-1604-1404.html

Make sure you get Selenium and Firefox at your compiuter.
how to run your codeception
using composer to get Codeception
you need to have composer in your system , if not download it from here .
https://getcomposer.org/
composer update

- rename the file tests/acceptance.suite.dist.yml to tests/acceptance.suite.yml
- edit the file ‘tests/acceptance.suite.yml’ according to your system needs
  for example your local is http://globalvision.dev/index.php we should change inside file
  tests/acceptance/suite.yml will be url: 'http://globalvision.dev/index.php'


Run the tests please execute the following commands (for the moment only working in Linux and MaxOS)

Step 1 : start your server
	Go to the place you download selenium
	Run : ‘java -Xmx256m -jar selenium-server-standalone-2.53.1.jar’

Step 2: Start codeception
	$composer install
	$composer update
	$ cd tests
	$vendor/bin/codecept bootstrap
	$vendor/bin/robo run:tests
