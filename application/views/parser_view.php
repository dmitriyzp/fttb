<form action="sendCSV" method="POST" enctype="multipart/form-data">
<input type="file" name="fileCSV" />
<p><input class="small-button" type="submit" id="send" value="Загрузить" onmouseup="$('#waitparse').show();" /></p>
</form>
<br />
<div id="waitparse" hidden="">
    <h1>ОЖИДАЙТЕ! ВЫПОЛНЯЕТСЯ ОБНОВЛЕНИЕ БД!!!</h1>
</div>
