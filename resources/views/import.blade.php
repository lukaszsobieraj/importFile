<!DOCTYPE html>
<html>

    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h2>Wybierz plik do importu</h2>
            {!! HTML::ul($errors->all()) !!}

            {{ Form::open(array('url' => '/file/read/', 'files' =>'true')) }}
            {{ csrf_field() }}
            <div class="form-group header">
                {!! Form::file('filename', Input::old('filename'), array('class' => 'form-control', 'required' => 'required')) !!}
            </div>

            {!! Form::submit('Załaduj plik', array('class' => 'btn btn-rounded btn-inline btn-info')) !!}

            {!! Form::close() !!}

        </div>
    </body>

</html>