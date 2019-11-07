<script type="text/javascript">
    function addnewLang(langCode, langId) {
        var mycounter = 1;

        $('#otherNamesContainer').append("<div class='row'>" +
            "<div class='form-group col-sm-4'> " +
            "<label for='companyName'>Name " + langCode + ":</label> " +
            "<input name='name" + langCode + "'  id='name" + langCode + "' type='text' class='form-control'  /></div></div>");

        $('#otherDescContainer').append("<div class='row'><div class='form-group col-sm-7'> " +
            "<label for='companyName'>Description " + langCode + ":</label> " +
            "<textarea name='description" + langCode + "' id='description" + langCode + "' type='text' class='form-control' ></textarea></div></div>"
        );
        $('textarea').ckeditor();
        var langrow = $("#" + langId).parent();
        $("#" + langId).remove();
        var newlangcode = '"' + langCode + '"';
        var newlangid = '"' + langId + '"';
        var oldval = $('#mataLangs').val()
        if (oldval.lengh < 2)
            $('#mataLangs').val(langCode);
        else
            $('#mataLangs').val(oldval + ',' + langCode);

        langrow.append("<button class='btn btn-danger' id='rembtn" + langCode + "' onclick='remLang(" + newlangcode + "," + newlangid + ")' type='button' ><i class='fa fa-minus' aria-hidden='true'></i></button>");

    }
    $(document).ready(function () {
        $('form').parsley();
    });
    function remLang(langCode, langId) {
        console.log("#name" + langCode);
        $("#name" + langCode).closest('.row').remove();
        $("#description" + langCode).closest('.row').remove();
        $("#seasonname" + langCode).closest('.row').remove();
        $("#seasondescription" + langCode).closest('.row').remove();
        var langrow = $("#rembtn" + langCode).parent();
        $("#rembtn" + langCode).remove();
        var newlangcode = '"' + langCode + '"';
        var oldval = $('#mataLangs').val()
        var values = oldval.split();

        removeA(values, langCode);


        console.log(values);

        $('#mataLangs').val(values);
        langrow.append("<button onclick='addnewLang(" + newlangcode + "," + langId + ")' id='" + langId + "' type='button' class='add-field btn btn-info' >" +
            "<i class='fa fa-plus' aria-hidden='rue'></i></button>");
    }

    function removeA(arr) {
        var what, a = arguments, L = a.length, ax;
        while (L > 1 && arr.length) {
            what = a[--L];
            while ((ax = arr.indexOf(what)) !== -1) {
                arr.splice(ax, 1);
            }
        }
        return arr;
    }
</script>

<script type="text/javascript">
    function addnewseasonLang(langCode, langId) {
        var mycounter = 1;

        $('#otherNamesContainer').append("<div class='row'>" +
            "<div class='form-group col-sm-4'> " +
            "<label for='companyName'>Name " + langCode + ":</label> " +
            "<input name='name" + langCode + "'  id='name" + langCode + "' type='text' class='form-control'  /></div></div>");

        //season name
        $('#otherNamesSeasonContainer').append("<div class='row'>" +
            "<div class='form-group col-sm-4'> " +
            "<label for='companyName'>Name " + langCode + ":</label> " +
            "<input name='seasonname" + langCode + "'  id='seasonname" + langCode + "' type='text' class='form-control'  /></div></div>");

        $('#otherDescContainer').append("<div class='row'><div class='form-group col-sm-7'> " +
            "<label for='companyName'>Description " + langCode + ":</label> " +
            "<textarea name='description" + langCode + "' id='description" + langCode + "' type='text' class='form-control' ></textarea></div></div>"
        );
        $('textarea').ckeditor();

        //season desc
        $('#otherDescSeasonContainer').append("<div class='row'><div class='form-group col-sm-7'> " +
            "<label for='companyName'>Description " + langCode + ":</label> " +
            "<textarea name='seasondescription" + langCode + "' id='seasondescription" + langCode + "' type='text' class='form-control' ></textarea></div></div>"
        );
        $('textarea').ckeditor();

        var langrow = $("#" + langId).parent();
        $("#" + langId).remove();
        var newlangcode = '"' + langCode + '"';
        var newlangid = '"' + langId + '"';
        var oldval = $('#mataLangs').val()
        if (oldval.lengh < 2)
            $('#mataLangs').val(langCode);
        else
            $('#mataLangs').val(oldval + ',' + langCode);

        langrow.append("<button class='btn btn-danger' id='rembtn" + langCode + "' onclick='remLang(" + newlangcode + "," + newlangid + ")' type='button' ><i class='fa fa-minus' aria-hidden='true'></i></button>");

    }
    $(document).ready(function () {
        $('form').parsley();
    });
    function remLang(langCode, langId) {
        console.log("#name" + langCode);
        $("#name" + langCode).closest('.row').remove();
        $("#description" + langCode).closest('.row').remove();
        var langrow = $("#rembtn" + langCode).parent();
        $("#rembtn" + langCode).remove();
        var newlangcode = '"' + langCode + '"';
        var oldval = $('#mataLangs').val()
        var values = oldval.split();

        removeA(values, langCode);


        console.log(values);

        $('#mataLangs').val(values);
        langrow.append("<button onclick='addnewLang(" + newlangcode + "," + langId + ")' id='" + langId + "' type='button' class='add-field btn btn-info' >" +
            "<i class='fa fa-plus' aria-hidden='rue'></i></button>");
    }

    function removeA(arr) {
        var what, a = arguments, L = a.length, ax;
        while (L > 1 && arr.length) {
            what = a[--L];
            while ((ax = arr.indexOf(what)) !== -1) {
                arr.splice(ax, 1);
            }
        }
        return arr;
    }
</script>
