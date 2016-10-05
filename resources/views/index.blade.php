@extends("layout")

@section("content")
<div class="container">
   <div class="jumbotron">
       <h1 class="display-3">Algeria's City Sniffer </h1>
       <p class="lead">This is a basic project to get the official list of all wilayas, dairas and communes (cities) of Algeria. With a converter tool into many formats (csv, xml, sql, etc.)</p>
       <hr class="m-y-2">
       <p class="lead">
          <a class="btn btn-primary btn-lg" href="{{ route('import.execute') }}" role="button">Import to your local database.</a>
       </p>
   </div>
</div>

<div class="container">
   <div class="row">
      <div class="card-group">
         <div class="card text-xs-center">
            <img class="card-img-top img-fluid" src="/img/json-file.png" alt="Json">
            <div class="card-block">
               <a href="{{ route('converter.embedded_json') }}" class="btn btn-primary">Download</a>
            </div>
         </div>
         <div class="card text-xs-center">
            <img class="card-img-top img-fluid" src="/img/csv.png" alt="CSV">
            <div class="card-block">
               <a href="#" class="btn btn-primary">Download</a>
            </div>
         </div>
         <div class="card text-xs-center">
            <img class="card-img-top img-fluid" src="/img/xml.png" alt="XML">
            <div class="card-block">
               <a href="#" class="btn btn-primary">Download</a>
            </div>
         </div>
         <div class="card text-xs-center">
            <img class="card-img-top img-fluid" src="/img/xls.png" alt="XLS">
            <div class="card-block">
               <a href="#" class="btn btn-primary">Download</a>
            </div>
         </div>
         <div class="card text-xs-center">
            <img class="card-img-top img-fluid" src="/img/pdf.png" alt="PDF">
            <div class="card-block">
               <a href="#" class="btn btn-primary">Download</a>
            </div>
         </div>
      </div>
   </div>
</div>
@stop
