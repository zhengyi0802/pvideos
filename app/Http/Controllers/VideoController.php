<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoCatagory;
use App\Models\Vendor;
use App\Models\Classification;
use App\Models\Actress;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use FFMpeg;

class VideoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pn = 20;
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $videos = Video::leftJoin('users', 'user_id', 'users.id')
                       ->leftJoin('vendors', 'vendor_id', 'vendors.id')
                       ->leftjoin('video_catagories', 'catagory_id', 'video_catagories.id')
                       ->select('videos.*', 'video_catagories.name as catagory', 'users.name as user', 'vendors.name as vendor')
                       ->latest()
                       ->paginate($pn);

        return view('videos.index', compact('videos'))
               ->with('i', (request()->input('page', 1) - 1) * $pn);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //var_dump(json_encode(auth()->user()));
        $user_id = auth()->user()->id;
        $catagories = VideoCatagory::where('status', true)
                              ->where('user_id', $user_id)
                              ->orWhere('user_id', 1)
                              ->orderBy('name', 'ASC')
                              ->get();
        $vendors = Vendor::orderBy('name', 'ASC')->get();
        $classifications = Classification::orderBy('name', 'ASC')->get();
        $actresses = Actress::orderBy('name', 'ASC')->get();

        return view('videos.create', compact('catagories'))
               ->with(compact('vendors'))
               ->with(compact('actresses'))
               ->with(compact('classifications'));
    }

    public function create2(Actress $actress)
    {
        //var_dump(json_encode(auth()->user()));
        $user_id = auth()->user()->id;
        $catagories = VideoCatagory::where('status', true)
                              ->where('user_id', $user_id)
                              ->orWhere('user_id', 1)
                              ->orderBy('name', 'ASC')
                              ->get();
        $vendors = Vendor::orderBy('name', 'ASC')->get();
        $classifications = Classification::orderBy('name', 'ASC')->get();

        return view('videos.create2', compact('catagories'))
               ->with(compact('vendors'))
               ->with(compact('actress'))
               ->with(compact('classifications'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required',
            'status' => 'required',
        ]);

        if ($request->catagory_id > 0) {
            $catagory = VideoCatagory::where('id', $request->catagory_id)->first();
        } else {
            $catagory = new VideoCatagory;
            $catagory->name = 'unknown';
        }
        $user_id = auth()->user()->id;
        $filename = 'u'.$user_id.'-'.time();
        $videofile = $filename.'.'.request()->file('video')->getClientOriginalExtension();
        $videoPath = 'storage/videos/'.$catagory->directory;
        $thumbnailPath = 'storage/thumbnails/'.$catagory->directory;
        $request->video->move(public_path($videoPath), $videofile);
        $video_url = env('APP_URL').'/'.$videoPath.'/'.$videofile;
        $thumbnail = $filename.'.jpg';
        $thumbnail_url = env('APP_URL').'/'.$thumbnailPath.'/'.$thumbnail;

        if ($request->thumbnail == '') {
            FFMpeg::fromDisk('videos/'.$catagory->name)
                  ->open($videofile)
                  ->getFrameFromSeconds(10)
                  ->export()
                  ->toDisk('thumbnails/'.$catagory->name)
                  ->save($thumbnail);
        } else {
           $extension = request()->file('thumbnail')->getClientOriginalExtension();
           if ($extension == 'jpeg') {
               $thumbnail = $filename.'.jpg';
           } else {
               $thumbnail = $filename.'.'.$extension;
           }
           $request->thumbnail->move(public_path($thumbnailPath), $thumbnail);
        }

        $video = new Video;

        $video->user_id         = $user_id;
        $video->catagory_id     = $request->input('catagory_id');
        $video->vendor_id       = $request->input('vendor_id');
        $video->article_number  = $request->input('article_number');
        $video->classifications = json_encode($request->input('classifications'));
        $video->actresses       = json_encode($request->input('actresses'));
        $video->keywords        = json_encode($request->input('keywords'));
        $video->title           = $request->input('title');
        $video->description     = $request->input('description');
        $video->status          = $request->input('status');
        $video->video_url       = $video_url;
        $video->publish_date    = $request->input('publish_date');
        $video->thumbnail       = $thumbnail_url;
        $video->save();
        return redirect()->route('videos.index')
                        ->with('success', 'Video stored successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        $video_id = $video->id;
        $user_id = auth()->user()->id;
        $data = Video::leftJoin('users', 'user_id', 'users.id')
                      ->leftJoin('vendors', 'vendor_id', 'vendors.id')
                      ->leftjoin('video_catagories', 'catagory_id', 'video_catagories.id')
                      ->select('videos.*', 'video_catagories.name as catagory', 'users.name as user', 'vendors.name as vendor')
                      ->where('videos.id', $video_id)->first();
        $classifications_id = json_decode($video->classifications);
        $actresses_id = json_decode($video->actresses);
        $classifications_array = array();
        if ($classifications_id) {
            foreach($classifications_id as $classification_id) {
                $classification = Classification::select('name')->where('id', $classification_id)->first();
                array_push($classifications_array, $classification->name);
            }
        }
        $classifications = implode(", ", $classifications_array);
        $actresses_array = array();
        if ($actresses_id) {
            foreach($actresses_id as $actress_id) {
                $actress = Actress::select('name')->where('id', $actress_id)->first();
                array_push($actresses_array, $actress->name);
            }
        }
        $actresses = implode(", ", $actresses_array);
        $video = array(
                 'user'            => $data->user,
                 'vendor'          => $data->vendor,
                 'catagory'        => $data->catagory,
                 'actresses'       => $actresses,
                 'classifications' => $classifications,
                 'article_number'  => $data->article_number,
                 'title'           => $data->title,
                 'description'     => $data->description,
                 'keywords'        => $data->keywords,
                 'thumbnail'       => $data->thumbnail,
                 'video_url'       => $data->video_url,
                 'publish_date'    => $data->publish_date,
                 'status'          => $data->status,
        );
        return view('videos.show', compact('video'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        $user_id = auth()->user()->id;
        $catagories = VideoCatagory::where('status', true)
                              ->where('user_id', $user_id)
                              ->orWhere('user_id', 1)
                              ->orderBy('name', 'ASC')
                              ->get();
        $vendors = Vendor::orderBy('name', 'ASC')->get();
        $classifications = Classification::orderBy('name', 'ASC')->get();
        $actresses = Actress::orderBy('name', 'ASC')->get();
        $array['classifications'] = json_decode($video->classifications, true);
        $array['actresses'] = json_decode($video->actresses, true);
        return view('videos.edit', compact('video'))
               ->with(compact('catagories'))
               ->with(compact('classifications'))
               ->with(compact('actresses'))
               ->with(compact('array'))
               ->with(compact('vendors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        $user_id = auth()->user()->id;

        //$request->merge(['user_id' => $user_id]);
        $data = $request->all();
        $data['user_id']         = $user_id;
        $data['article_number']  = $request->article_number;
        $data['classifications'] = json_encode($request->input('classifications'));
        $data['actresses']       = json_encode($request->input('actresses'));
        $data['keywords']        = json_encode($request->input('keywords'));


        $video->update($data);
/*
        var_dump($video->toArray());
        echo "<br><br>";
        var_dump($data);
*/
        return redirect()->route('videos.index')
                        ->with('success', 'Video updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        $video->delete();

        return redirect()->route('videos.index')
                        ->with('success', 'Video deleted successfully');
    }

    public function destroy2(Actress $actress, Video $video)
    {
        $video->delete();

        return view('actresses.show', compact('actress'));
    }

    public function search(Request $request)
    {
        $title = $request->input('title');
        $video = Video::where('title', 'LIKE', '%'.$title.'%')->first();
        if ($video == null) {
           return $this->index();
        }
        return view('videos.show', compact('video'));
    }

    public function queryByActress($subcommand, $name)
    {
        $result= null;
        if ($subcommand == 'list') {
            $actresses = Actress::select('id', 'name')
                                ->get();
            if ($actresses) {
                $result = $actresses->toArray();
            }
        } else if ($subcommand == 'videos') {
            $actress = Actress::where('name', 'LIKE', $name.'%')->first();
            $videosdata = Video::leftJoin('vendors', 'vendor_id', 'vendors.id')
                               ->leftjoin('video_catagories', 'catagory_id', 'video_catagories.id')
                               ->select('videos.*', 'video_catagories.name as catagory', 'vendors.name as vendor')
                               ->get();
            $videos = array();
            foreach($videosdata as $data) {
                $acts = json_decode($data->actresses, true);
                if (gettype($acts) == 'array') {
                    foreach($acts as $act) {
                        if ($actress->id == $act) {
                            array_push($videos, $data->toArray());
                        }
                    }
                } else if (gettype($acts) == 'string') {
                    if ($actress->id == $acts) {
                        array_push($videos, $data->toArray());
                    }
                }
            }
            $result = $videos;
        }
        return $result;
    }

    public function queryByClassification($subcommand, $name)
    {
        $result = null;
        if ($subcommand == 'list') {
            $classifications= Classification::select('id', 'name')
                                            ->get();
            if ($classifications) {
                $result = $classifications->toArray();
            }
        } else if ($subcommand == 'videos') {
            $classification = Classification::where('name', 'LIKE', $name)->first();
            $videosdata = Video::leftJoin('vendors', 'vendor_id', 'vendors.id')
                             ->leftjoin('video_catagories', 'catagory_id', 'video_catagories.id')
                             ->select('videos.*', 'video_catagories.name as catagory', 'vendors.name as vendor')
                             ->get();
            $videos = array();
            foreach($videosdata as $data) {
                $classes = json_decode($data->classifications, true);
                if (gettype($classes) == 'array') {
                    foreach($classes as $class) {
                        if ($class == $classification->id) {
                            array_push($videos, $data);
                        }
                    }
                } else if (gettype($classes) == 'string') {
                    if ($classes == $classification->id) {
                        array_push($videos, $data);
                    }
                }
            }
            $result = $videos;
        }
        return $result;
    }

    public function queryByVendor($subcommand, $name)
    {
        $result = null;
        if ($subcommand == 'list') {
          $vendors = Vendor::select('id', 'name')
                           ->get();
          if ($vendors) {
              $result = $vendors->toArray();
          }
        } else if ($subcommand == 'videos') {
          $vendor = Vendor::where('name', 'LIKE', $name.'%')->first();
          if ($vendor) {
              $videos = Video::leftJoin('vendors', 'vendor_id', 'vendors.id')
                             ->leftjoin('video_catagories', 'catagory_id', 'video_catagories.id')
                             ->select('videos.*', 'video_catagories.name as catagory', 'vendors.name as vendor')
                             ->where('vendor_id', $vendor->id)
                             ->get();
              if ($videos) {
                  $result = $videos->toArray();
              }
          }
        }
        return $result;
    }

    public function queryByVideo($subcommand, $name)
    {
        $result = null;
        if ($subcommand == 'list') {
            $videos = Video::leftJoin('vendors', 'vendor_id', 'vendors.id')
                           ->leftjoin('video_catagories', 'catagory_id', 'video_catagories.id')
                           ->select('videos.*', 'video_catagories.name as catagory', 'vendors.name as vendor')
                           ->where('videos.status', true)
                           ->orderBy('videos.id', 'DESC')
                           ->get();
            if ($videos) {
                $result = $videos->toArray();
            }
        } else if ($subcommand == 'search') {
            $videos = Video::leftJoin('vendors', 'vendor_id', 'vendors.id')
                           ->leftjoin('video_catagories', 'catagory_id', 'video_catagories.id')
                           ->select('videos.*', 'video_catagories.name as catagory', 'vendors.name as vendor')
                           ->where('article_number', 'LIKE', '%'.$name.'%')
                           ->orWhere('videos.title', 'LIKE', '%'.$name.'%')
                           ->orderBy('videos.id', 'DESC')
                           ->get();
            if ($videos) {
                $result = $videos->toArray();
            }
        }
        return $result;
    }

    public function query(Request $request)
    {
        $page_length = 10;

        $command = $request->input('command');
        $subcommand = $request->input('subcommand');
        $name = $request->input('name');
        $page = 1;
        if ($request->input('page')) {
            $page = $request->input('page');
        }
        $result = null;
        if ($command == 'video') {
            $result = $this->queryByVideo($subcommand, $name);
        } else if ($command == 'vendor') {
            $result = $this->queryByVendor($subcommand, $name);
        } else if ($command == 'actress') {
            $result = $this->queryByActress($subcommand, $name);
        } else if ($command == 'classification') {
            $result = $this->queryByClassification($subcommand, $name);
        }
        if (($subcommand == 'videos') || ($command == 'video')) {
            $pages = (int) (count($result) / $page_length + 1);
            $offset = ($page-1) * $page_length;
            $slice = array_slice($result, $offset, $page_length, false);
            $output = array(
                  'page'    => $page,
                  'pages'   => $pages,
                  'videos'  => $slice,
            );
        } else {
            $output = $result;
        }
        return json_encode($output);
    }

}
