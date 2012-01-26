import os

from django.conf import settings
from django.test import TestCase

import django_openid


class TestCaseBase(TestCase):
    
    def setUp(self):
        self.old_template_dirs = settings.TEMPLATE_DIRS
        settings.TEMPLATE_DIRS = (
            os.path.join(os.path.dirname(django_openid.__file__), "templates"),
        )
    
    def tearDown(self):
        settings.TEMPLATE_DIRS = self.old_template_dirs