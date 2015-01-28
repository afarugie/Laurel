Laurel Framework v0.9
======
<p>
The Laurel Framework is a small MVC framework that promotes developers to produce: structured, clean, and functional(error free) unit tested applications in PHP. Laurel comes default with 4 packages: ActionController, ActionDispatch, ActionView, and the optional ActiveRecord. 
</p>
<h3>Installing Laurel</h3>
<p>
Navigate to the directory in which you want to install laurel. This is generally your webservers root path.
</p>
<pre>
cd /var/www
</pre>
<p>
Clone the Laurel Repository from GitHub
</p>
<pre>
git clone https://github.com/Laurel-Software/Laurel.git
</pre>
<p>
<strong>Note:</strong> NEVER have ANY of the Laurel files available for the public ( excluding public_html ). This is a major security risk and Laurel does not come with any default configurations for this purpose.
</p>
<p>
Copy the files into your webservers root path, be sure to back up your original public_html files or rename the Laurel directory to your sites name and update your webservers config.
</p>
<pre>
cp -r Laurel/ /var/www/
# or
mv Laurel laurel-software.org
</pre>
<h3>Run Unit Tests ( requires PHPUnit )</h3>
<p>
Navigate to the test directory where you installed Laurel and run
</p>
<pre>
phpunit --testsuite Controllers
</pre>
<p>
If you have any failed assertions, navigate through the Laurel directory and execute the test suites for each package to locate any missing dependencies.
</p>
<h3>All Tests Passed</h3>
<p>
Start up your webserver if you haven't already done so and navigate to your domain. You should see the following response:
</p>
<pre>
See me in Application/Views/index/index.php 
</pre>
<p>
Laurel currently comes with default configurations for Apache.
</p>
<h3>Ongoing Development</h3>
<ul>
<li>Documentation</li>
<li>Fix a bug where the controller tries to load all files in the helpers directory</li>
<li>Implement a Core Session Library</li>
<li>Implement a Caching Library</li>
<li>Finish Implementing the Extensions Functionality</li>
<li>Remove any redundant files, code, comments, etc.</li>
</ul>
