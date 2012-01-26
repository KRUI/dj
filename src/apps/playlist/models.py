import datetime

from django.db import models
from pinax.apps.account.models import Account

class Play(models.Model):
    track = models.ForeignKey('Song')
    timestamp = models.DateTimeField(null=True, blank=True, default=datetime.datetime.now)
    user = models.ForeignKey(Account)
    request = models.BooleanField()
 
class Song(models.Model):
    name = models.CharField(max_length=50)
    album = models.ForeignKey('Album',null=True,blank=True)
    artist = models.ForeignKey('Artist',null=True,blank=True)

class Album(models.Model):
    name = models.CharField(max_length=50)
    color = models.ForeignKey('Color')
    
class Artist(models.Model):
    name = models.CharField(max_length=50)

class Color(models.Model):
    name = models.CharField(max_length=50)
    hex_value = models.CharField(max_length=6)
    
    