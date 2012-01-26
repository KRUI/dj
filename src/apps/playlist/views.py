from django.shortcuts import *
from playlist.models import *

def default(request):
    return render_to_response('playlist.html',{})