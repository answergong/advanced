<?php

namespace backend\controllers;

use backend\models\BlogCategory;
use Yii;
use backend\models\Blog;
use backend\models\searches\BlogSearchController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Exception;

/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends Controller
{
    /**
     * 初始化方法init()
     *
     * @author   gongxiangzhu
     * @dateTime 2018/6/5 17:55
     *
     * @return mixed
     */
    public function init()
    {
        parent::init();

    }

    public function onBeforeInsert()
    {
        Yii::info('我是新增前触发的事件,小前qian');
    }

    public function onAfterInsert()
    {
        Yii::info('我是新增后触发的事件,小后hou');
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Blog models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlogSearchController();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Blog model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */

    public function actionCreate()
    {
        $this->on(self::EVENT_BEFORE_ACTION, [$this, 'onBeforeInsert']);
        $this->on(self::EVENT_AFTER_ACTION, [$this, 'onAfterInsert']);
        $model = new Blog();
        // 注意这里调用的是validate，非save,save我们放在了事务中处理了
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // ($file = Upload::up($model, 'file')) && $model->file = $file;
                /**
                 * current model save
                 */
                $model->save(false);
                // 注意我们这里是获取刚刚插入blog表的id
                $blogId = $model->id;
                /**
                 * batch insert category
                 * 我们在Blog模型中设置过category字段的验证方式是required,因此下面foreach使用之前无需再做判断
                 */
                $data = [];
                foreach ($model->category as $k => $v) {
                    // 注意这里的属组形式[blog_id, category_id]，一定要跟下面batchInsert方法的第二个参数保持一致
                    $data[] = [$blogId, $v];
                }
                // 获取BlogCategory模型的所有属性和表名
                $blogCategory = new BlogCategory;
                $attributes = ['blog_id', 'category_id'];
                $tableName = $blogCategory::tableName();
                $db = BlogCategory::getDb();
                // 批量插入栏目到BlogCategory::tableName表
                $db->createCommand()->batchInsert(
                    $tableName,
                    $attributes,
                    $data
                )->execute();
                // 提交
                $transaction->commit();
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                // 回滚
                $transaction->rollback();
                throw $e;
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Blog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                /**
                 * current model save
                 */
                $model->save(false);
                // 注意我们这里是获取刚刚插入blog表的id
                $blogId = $model->id;
                /**
                 * batch insert category
                 * 我们在Blog模型中设置过category字段的验证方式是required,因此下面foreach使用之前无需再做判断
                 */
                $data = [];
                foreach ($model->category as $k => $v) {
                    // 注意这里的属组形式[blog_id, category_id]，一定要跟下面batchInsert方法的第二个参数保持一致
                    $data[] = [$blogId, $v];
                }
                // 获取BlogCategory模型的所有属性和表名
                $blogCategory = new BlogCategory;
                $attributes = ['blog_id', 'category_id'];
                $tableName = $blogCategory::tableName();
                $db = BlogCategory::getDb();
                // 先全部删除对应的栏目
                $sql = "DELETE FROM `{$tableName}`  WHERE `blog_id` = :bid";
                $db->createCommand($sql, ['bid' => $id])->execute();

                // 再批量插入栏目到BlogCategory::tableName()表
                $db->createCommand()->batchInsert(
                    $tableName,
                    $attributes,
                    $data
                )->execute();
                // 提交
                $transaction->commit();
                return $this->redirect(['index']);
            } catch (Exception $e) {
                // 回滚
                $transaction->rollback();
                throw $e;
            }
        } else {
            // 获取博客关联的栏目
            $model->category = BlogCategory::getRelationCategorys($id);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Blog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
