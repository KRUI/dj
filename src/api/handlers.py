# API Handlers for krui-dj3
# Most of the API work is done via django-piston
import re

from piston.handler import BaseHandler
from piston.utils import rc, throttle

from krui-dj3.apps.playlist.models import Play

class PlayHandler(BaseHandler):
    allowed_methods = ('GET',)
    model = Play
    
    def read(self, request, post_slug):
        