<?php
/**
 * Created by PhpStorm.
 * User: danielshen
 * Date: 2019/12/5
 * Time: 19:51
 */

namespace Shencongcong\LaravelCurd;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Shencongcong\LaravelCurd\Exceptions\HttpException;

class  LaravelCurd
{

    use AdapterLaravel;

    protected $app;

    protected $request;

    protected $data;

    protected $model;

    protected $version;

    const NOT_FIND_RESOURCE='没有找到对应操作资源信息';

    public function __construct(Container $app)
    {
        $this->version = app()::VERSION;

        $this->app  = $app;

        $this->request = $app->request;

        $this->data = $this->request->except('_url');
    }

    public function make($model)
    {
        if ( !$model instanceof Model ) {
            $model = $this->app->make($model);
            if ( !$model instanceof Model ) {
                throw new HttpException("Class {$model} must be an instance of Illuminate\\Database\\Eloquent\\Model");
            }
        }
        $this->model = $model;

        return $this;
    }

    public function add()
    {
        $m = $this->model;
        $data = $this->data;
        try {
            DB::beginTransaction();
            // 重新组装 $data
            $tmpData = $this->laravelEvent($this->version,'curd:filterData', [$m, $data]);

            $data = count($tmpData) == 0?$data:$tmpData;

            $this->laravelEvent($this->version,'curd:beforeAdd', [$m, $data]);

            $model = $m->create($data);

            $this->laravelEvent($this->version,'curd:afterAdd', [$model, $data]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            throw new HttpException($e->getMessage());
        }

        return $model;
    }

    public function update()
    {
        $id = $this->request->id;
        $id = $id ? $id : $this->request->input('id', 0);
        $data = $this->data;
        try {
            $m = property_exists($this->model, 'id') && !empty($this->model->id) ? $this->model
                : $this->model->findOrfail($id);

            DB::beginTransaction();

            $tmpData = $this->laravelEvent($this->version,'curd:filterData', [$m, &$data]);
            $data = count($tmpData) == 0?$data:$tmpData;

            if ( !empty($data) ) {
                foreach ($this->model->getFillAble() as $key) {
                    if ( isset($data[$key]) ) {
                        $m->$key = $data[$key];
                    }
                }
            }

            $this->laravelEvent($this->version,'curd:beforeEdit', [$m, $data]);

            $m->save();

            $this->laravelEvent($this->version,'curd:afterEdit', [$m, $data]);

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            throw new HttpException($e->getMessage());
        }

        return $m;
    }

    public function list($pageSize = 0,$withTrashed = true)
    {
        $data = $this->data;

        $builder = $this->model->query();

        $this->laravelEvent($this->version,'curd:beforeQuery', [$builder, $data]);

        if ($withTrashed) {
            $builder->withTrashed();
        }

        return $pageSize ? $builder->paginate($pageSize) : $builder->get();
    }

    public function detail()
    {
        $id = $this->request->id;

        $m = $this->model->query();

        $this->laravelEvent($this->version,'curd:afterDetail', [$m, $id]);

        $m = $m->find($id);

        if ( !$m ) {
            throw new HttpException(self::NOT_FIND_RESOURCE);
        }

        return $m;
    }

    public function delete($hasForce = false)
    {
        $id = $this->request->id;
        $ids = $id ? [$id] : $this->request->input('ids');
        try {
            DB::beginTransaction();

            $this->laravelEvent($this->version,'curd:beforeDelete', [$ids]);

            if ( $hasForce ) {
                $this->model->withTrashed()->whereIn('id', $ids)->forceDelete();
            }
            else {
                $this->model->destroy($ids);
            }

            $this->laravelEvent($this->version,'curd:afterDelete', [$ids]);
            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            throw new HttpException($e->getMessage());
        }

        return $ids;
    }

    public function restore()
    {
        $id  = $this->request->id;
        $ids = $id ? [$id] : $this->request->input('ids');

        try {
            DB::beginTransaction();

            $this->laravelEvent($this->version,'curd:beforeRestore', [$ids]);

            $this->model->withTrashed()->whereIn('id', $ids)->restore();

            $this->laravelEvent($this->version,'curd:afterRestore', [$ids]);

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            throw new HttpException($e->getMessage());
        }

        return $ids;
    }

}