import imp
import os
import sys


sys.path.insert(0, os.path.dirname(__file__))

wsgi = imp.load_source('wsgi', 'fmaps_scraper_v1.py')
application = wsgi.application
