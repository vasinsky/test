<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->

<script id="template-download" type="text/x-tmpl">


{% for (var i=0, file; file=o.files[i]; i++) { %}

<!-- -->
{% if (file.url) { %}
<!-- -->

    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
         
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
         
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
       
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}

                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
       
       
        </td>
    </tr>
<!-- -->    
{% } %}
<!-- -->
 {% } %}
</script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="../../<?=PATH;?>app/js/uploader/js/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="../../<?=PATH;?>app/js/uploader/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="../../<?=PATH;?>app/js/uploader/js/canvas-to-blob.min.js"></script>
<!-- blueimp Gallery script -->
<script src="../../<?=PATH;?>app/js/uploader/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="../../<?=PATH;?>app/js/uploader/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="../../<?=PATH;?>app/js/uploader/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="../../<?=PATH;?>app/js/uploader/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="../../<?=PATH;?>app/js/uploader/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="../../<?=PATH;?>app/js/uploader/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="../../<?=PATH;?>app/js/uploader/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="../../<?=PATH;?>app/js/uploader/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="../../<?=PATH;?>app/js/uploader/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="../../<?=PATH;?>app/js/uploader/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="js/cors/jquery.xdr-transport.js"></script>
<![endif]-->

