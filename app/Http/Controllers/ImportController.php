<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Jobs\ImportDairasJob;

class ImportController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        //
    }

    /**
    * Excute the import on a queue
    *
    * @return Response
    */
    public function execute()
    {
        dispatch(new ImportDairasJob());

        return redirect()->to('/')->with(
            'message',
            "The data is being imported to your local database. This may take several minutes..."
        );
    }


}
