<p><a class="translation" translation="prog_web"></a><br><a class="translation" translation="made_by"></a><?php echo $_SESSION["author"]?><br></p>
<form id="getForm">
    <label for="langGet" class="translation" translation="lang_selection"></label>
    <select name="lang" id="langGet">
        <option value="fr_fr" class="translation" translation="fr_fr"></option>
        <option value="en_us" class="translation" translation="en_us"></option>
        <option value="en_pt" class="translation" translation="en_pt"></option>
        <option value="ja_jp" class="translation" translation="ja_jp"></option>
    </select>
    <button type="submit" class="translation" translation="update"></button>
</form>