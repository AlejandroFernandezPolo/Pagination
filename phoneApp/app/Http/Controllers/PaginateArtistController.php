<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class PaginateArtistController extends Controller
{

    private $bladeFolder = 'paginateartist';
    const PAGE = 1;
    const RPP = 10;
    const ORDERBY = 'id';
    const ORDERTYPE = 'asc';
    private function getBladeFolder(string $folder) {
        return $this->bladeFolder . '.' . $folder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rpp = self::getFromRequest($request, 'rowsPerPage', self::RPP);
        $orderby = self::getFromRequest($request, 'orderBy', self::ORDERBY);
        $ordertype = self::getFromRequest($request, 'orderType', self::ORDERTYPE);
        $q = self::getFromRequest($request, 'q', null);
        if($q == null) {
            $artists = Artist::orderBy($orderby, $ordertype)->orderBy('name', 'asc')->paginate($rpp);
        }else {
            $artists = Artist::where('name', 'like', '%'.$q.'%')
                ->orWhere('id', $q)
                ->orWhere('idoltro', 'like', '%'.$q.'%')
                ->orWhere('idargo', 'like', '%'.$q.'%')
                ->orderBy($orderby, $ordertype)
                ->orderBy('name', 'asc')
                ->paginate($rpp);
        }
        //$artists = Artist::where('id', '>', 2)->orderBy($orderby, $ordertype)->orderBy('name', 'asc')->paginate($rpp);
        return view($this->getBladeFolder('index'),
            [
                'artists' => $artists,
                'orderBy' => $orderby,
                'orderType' => $ordertype,
                'q' => $q,
                'rpp' => $rpp,
                'rpps' => self::getRowsPerPage()
            ]);
    }
    
    private static function getFromRequest($request, $name, $defaultValue){
        $value = $defaultValue;
        if($request->$name != null) {
            $value = $request->$name;
        }
        return $value;
    }
    
    //PAGINACIÓN A MANO
    function index2 (Request $request) {
        //1º
        $rpp = self::getFromRequest($request, 'rowsPerPage', self::RPP);
        //2º
        $page = self::getFromRequest($request, 'page', self::PAGE);
        //3º
        $calculo = $rpp * ($page - 1);
        $sql = "select * from artist limit $calculo, $rpp";
        $artists = DB::select($sql);
        $total = DB::table('artist')->count();
        $pages = ceil($total / $rpp);
        return view($this->getBladeFolder('index2'),
            [
                'artists' => $artists,
                'pages' => $pages,
                'page' => $page,
                'rpp' => $rpp,
                'rpps' => self::getRowsPerPage()
            ]);
    }
    
    private static function getRowsPerPage() {
        return [
            3 => 3,
            10 => 10,
            25 => 25,
            50 => 50
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
}