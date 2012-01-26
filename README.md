KRUI DJ 3
=========

KRUI DJ 3 is the newest iteration of KRUI's staff software, designed for use by DJs and among the staff.  We are a collaborative group interested in writing a full-featured content platform for KRUI.  To focus on project code rather than technicalities, we are using the Django/Pinax stack.


Requirements
------------
## Python 2.6+ (but not 3.X)
You can check the version by opening a shell and typing `$ python --version` into the prompt.  If you have a version lower than 2.6 or a version 3.X, get the latest 2.X version from [Python Software Foundation][1].

Note: as of now (Jan. 2011) Python 3.x is not fully supported by [Django][2], and not supported at all by [Pinax][3].  This is likely to change in the future as Python 3.x adoption increases.
	
## pip
We use [pip][4] to install Python modules easily.  To ensure that you have pip, type `$ pip --version`.  If you don't have pip, install it from the site.

## virtualenv
A major concern among web development teams is the assurance that the development environment each of us use be standardized across different platforms.  To solve this concern, we use virtualenv to ensure that the environment is the same on your computer as it is on mine.  With virtualenv, you don't need to worry about having a handful of Python modules installed on your system, and you can focus on the code.

You can install virtualenv using pip.  From the command line, type `$ pip install virtualenv`.  Check that it worked by creating a new virtual environment for yourself; type `$ virtualenv practice-env`.  Now go ahead and start the environment by typing `$ source practice-env/bin/activate`.  You should now see (practice-env) immediately preceding your terminal prompt.  This indicates that you are currently in the practice-env environment. For the project code, you will type `$ source env/bin/activate` from the root directory.

Developers
----------
If you are interested in helping with the krui-dj3 project, contact us at IT@krui.fm.  


License
--------------------
Copyright 2012 KRUI-FM

Licensed under the BSD License.

[1]: http://python.org/download       		"Python"
[2]: http://djangoproject.com         		"Django"
[3]: http://pinaxproject.com		  		"Pinax"
[4]: http://pypi.python.org/pypi/pip  		"pip"
[5]: http://pypi.python.org/pypi/virtualenv "virtualenv"