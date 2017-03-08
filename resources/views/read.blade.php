<!DOCTYPE html>
<html>

    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body>
        <div class="container">
            <h4>Załadowano następujący format pliku: {{ $fileType}}</h4>
            <div>Wybierz plik do pobrania:</div>
            {!! HTML::ul($errors->all()) !!}
            {{ Form::open(array('url' => '/store/filedata', 'class' => 'formularz')) }}
            {{ csrf_field() }}
            <select   class="form-control input-sm select" id="extension_types">
                @foreach($extensions as  $extension)
                <option value="{{ $extension }}">
                    {{ $extension }}
                    @endforeach
                </option>
            </select>
            {!! Form::text('filePath', $filePath, array('class' => 'hidden')) !!}
            {!! Form::text('fileName', $fileName, array('class' => 'hidden')) !!}
            {!! Form::text('fileExtension', $filePath, array('class' => 'hidden extension')) !!}
            {!! Form::submit('Wykonaj', array('class' => 'btn btn-default')) !!}

            {!! Form::close() !!}
        </div>
        <script>
$("select").on("click", function () {
    var extension = $('#extension_types :selected').text();
    $(".extension").val(extension);
});
        </script>
    </body>
</html>