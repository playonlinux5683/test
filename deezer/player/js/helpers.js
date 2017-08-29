var CurrentTrack = new Object;

function loadAlbum(element, albumId) {
  DZ.api('/album/' + albumId , function(response) {
    loadTracksToAdd(response.tracks.data);
    element.disabled = true;
  });
}
function loadPlaylist(playlistId, autoplay = false, index = 0, callback = false, offset = 0) {
  try {
    playlistId = parseInt(playlistId, 10);
    DZ.player.playPlaylist(playlistId, autoplay, index, callback, offset);
  } catch(e) {
    console.error(e);
  }
}

function getTracks(id) {
  var tracks = new Array;
  var selectedItems = new Object;

  var select = document.getElementById(id);

  for (var i = 0; i < select.options.length; i++) {
    if (select.options[i].selected === true) {
      tracks.push({
        id: select.options[i].value,
        title: select.options[i].innerText
      });
      selectedItems[i] = true;
    }
  }
  for (var i = select.options.length; i >=0 ; i--) {
    if (selectedItems[i] == true) {
      select.remove(i);
    }
  }
  return tracks;
}

function loadIntoList(id, elements) {
  elements.forEach(function(element) {
    var option = '<option value="' + element.id + '">' + element.title + '</option>';
    document.getElementById(id).innerHTML += option;
  });
}

function getTrackIds(tracks) {
  return Array.prototype.map.call(tracks, function(track) {
    return parseInt(track.id, 10);
  });
}

function loadTracksToAdd(tracks) {
  loadIntoList('listOfTracksToAdd', tracks);
}

function addTracks() {
  var tracks = getTracks('listOfTracksToAdd');
  if (tracks.length) {
    var trackIds = getTrackIds(tracks);
    var playlistId = document.getElementById('listOfPlayLists').value;
    DZ.api('/playlist/' + playlistId + '/tracks', 'POST', {songs: trackIds}, function(response) {
      var index = DZ.player.getCurrentIndex();
      var offset = CurrentTrack.offset;
      loadPlaylist(playlistId, true, index, false, offset);
    });
  }
}

function loadPlayLists() {
  DZ.api('user/me/playlists', function(response){
    console.log(response);
    var playlists = Array.prototype.filter.call(response.data, function(data) {
      return (data.nb_tracks > 0);
    });
    loadIntoList('listOfPlayLists', playlists);
    document.getElementById('listOfPlayLists').onchange();

  });
}

function SignIn() {
  DZ.login(function(response) {
    if (response.authResponse) {
      window.location.href = 'player.html';
    } else {
      console.log('User cancelled login or did not fully authorize.');
      // window.location.href = 'index.html';
    }
  }, {perms: 'basic_access, email, manage_library, listening_history'});

}
function SignOut() {
  DZ.logout(function() {
    window.location.href = 'index.html';
  });
}

function InitPlayer(withPlayer = false) {
  DZ.DEBUG = true;
  var params = {
    appId: '249962',
    channelUrl: 'http://localhost:8001/player/channel.html'
  };

  if (withPlayer) {
    params.player = {
      container: 'player',
      width: 650,
      height: 300,
      playlist: true,
      onload: function(response) {
        console.log('DZ.player is ready', response);

        DZ.Event.subscribe('player_position', function(infos) {
          CurrentTrack.offset = infos[0];
        });
        DZ.Event.subscribe('tracklist_changed', function(infos) {
        });
     }
    }
  }
  DZ.init(params);
}
