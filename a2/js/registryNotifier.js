function notifyRegistry() {
  var serverName = $('#server-name').val();
  var ip = $('#ip').val();

  resetStatusMessages();

  $.get("notifyRegistry.php", {
    name: serverName,
    ip: ip
  })
    .success(function() {
      msg('Server wurde erfolgreich angemeldet und hat die IP-Liste der anderen Server erhalten. Viel Spaß!');
      showIpList();
    })
    .fail(function() {
      error('Es ist leider etwas schief gelaufen.');
    });

  function showIpList(array) {
    //var list;
    //
    //jQuery.each( arr, function( i, val ) {
    //  $( "#" + val ).text( "Mine is " + val + "." );
    //
    //  // Will stop running after "three"
    //  return ( val !== "three" );
    //});
    //
    //  '<div class="entry" style="display:none;">' +
    //  '<p>' +
    //  response.message.time +
    //  ', ' + response.message.from +
    //  ': ' + response.message.message +
    //  '</p>' +
    //  '</div>';
    //
    //$(list).appendTo('.ip-list-container').fadeIn('slow');
  }
}