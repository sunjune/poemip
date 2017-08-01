<?php

// 获取指定id的项目信息
function get_project_by_id($PoemDB, $postdata, $order = '')
{
    $project_id = $postdata['project_id'];
    
    if (intval($project_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的项目ID';
        return $rs_result;
    }
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 定义查询语句
    // 在项目信息表中，查询指定id的项目数据
    $sql = "SELECT project_id, project_name, project_desc, create_date, last_update, user_name
    FROM tb_project
    WHERE project_id=$project_id";
    
    try {
        $rs = $pdo->query($sql);
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    return $rs_result;
}

/*
 * 查询数据库，返回所有的项目id和名称，默认按id倒序排序
 */
function get_project_list($PoemDB, $order = '')
{
    // 用于返回执行结果的数组
    $rs_result = array();
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 定义查询语句
    // 在项目信息表和项目设备关联表中，查询各项目id和名称，以及各自对应的设备数量
    $sql = "SELECT a.project_id, a.project_name, b.total
                FROM tb_project AS a
                LEFT JOIN
                (
                SELECT COUNT(id) AS total, project_id
                FROM tb_project_equipment
                GROUP BY project_id) AS b ON a.project_id=b.project_id
                ORDER BY a.project_id DESC";
    
    try {
        $rs = $pdo->query($sql);
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    return $rs_result;
}

// 获取指定id的设备信息
function get_equipment_by_id($PoemDB, $postdata, $order = '')
{
    $equipment_id = $postdata['equipment_id'];
    
    if (intval($equipment_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的设备ID';
        return $rs_result;
    }
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 定义查询语句
    // 在设备信息表中，查询指定id的设备数据
    $sql = "SELECT equipment_id, equipment_name, equipment_desc, equipment_model, equipment_spec, equipment_param, create_date, last_update, user_name
    FROM tb_equipment
    WHERE equipment_id=$equipment_id";
    
    try {
        $rs = $pdo->query($sql);
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    return $rs_result;
}

// 读取指定项目id下所有设备，默认按id倒序排序
function get_equipment_list_by_project_id($PoemDB, $postdata, $order = '')
{
    $project_id = $postdata['project_id'];
    
    if (intval($project_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的项目ID';
        return $rs_result;
    }
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 定义查询语句
    $sql = "SELECT c.equipment_name, d.*
            FROM tb_equipment AS c
            RIGHT JOIN
            (
              SELECT a.*, b.total
              FROM 
              (
                SELECT project_id, equipment_id
                FROM tb_project_equipment
                WHERE project_id=$project_id
              ) AS a
              LEFT JOIN
              (
                SELECT COUNT(id) AS total, project_id, equipment_id
                FROM tb_project_equipment_supplier
                WHERE project_id=$project_id
                GROUP BY project_id, equipment_id
              ) AS b
              ON a.equipment_id=b.equipment_id AND a.project_id=b.project_id
            ) AS d
            ON c.equipment_id=d.equipment_id
            ORDER BY c.equipment_id DESC";
    
    try {
        $rs = $pdo->query($sql);
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    return $rs_result;
}

// 读取不属于指定项目id下的所有设备，默认按id倒序排序
function get_equipment_list_not_for_project_id($PoemDB, $postdata, $order = '')
{
    $project_id = $postdata['project_id'];
    
    if (intval($project_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的项目ID';
        return $rs_result;
    }
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 定义查询语句
    $sql = "SELECT a.equipment_id, a.equipment_name
            FROM tb_equipment a
            WHERE a.equipment_id NOT IN
            (
            SELECT equipment_id
            FROM tb_project_equipment
            WHERE project_id=$project_id
            )
            ORDER BY a.equipment_id DESC";
    
    try {
        $rs = $pdo->query($sql);
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    return $rs_result;
}

// 读取指定id的供应商信息
function get_supplier_by_id($PoemDB, $postdata)
{
    $supplier_id = $postdata['supplier_id'];
    
    if (intval($supplier_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的供应商ID';
        return $rs_result;
    }
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 定义查询语句
    $sql = "SELECT supplier_id, supplier_name, supplier_desc, create_date, last_update, user_name
            FROM tb_supplier
            WHERE supplier_id=$supplier_id";
    
    try {
        $rs = $pdo->query($sql);
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    return $rs_result;
}

// 读取指定项目和设备id下所有供应商，默认按id倒序排序
function get_supplier_list_by_full_path($PoemDB, $postdata, $order = '')
{
    $project_id = $postdata['project_id'];
    $equipment_id = $postdata['equipment_id'];
    
    if (intval($project_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的项目ID';
        return $rs_result;
    }
    if (intval($equipment_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的设备ID';
        return $rs_result;
    }
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 定义查询语句
    $sql = "SELECT c.supplier_name, d.*
            FROM tb_supplier AS c
            RIGHT JOIN
            (
                SELECT a.*, b.total
                FROM
                (
                    SELECT project_id, equipment_id, supplier_id
                    FROM tb_project_equipment_supplier
                    WHERE project_id=$project_id AND equipment_id=$equipment_id
                ) AS a
                LEFT JOIN
                (
                    SELECT COUNT(issue_id) AS total, project_id, equipment_id, supplier_id
                    FROM tb_issue
                    WHERE project_id=$project_id AND equipment_id=$equipment_id
                    GROUP BY project_id, equipment_id, supplier_id
                ) AS b ON a.project_id=b.project_id AND a.equipment_id=b.equipment_id AND a.supplier_id=b.supplier_id
            ) AS d ON c.supplier_id=d.supplier_id
            ORDER BY c.supplier_id DESC";
    
    try {
        $rs = $pdo->query($sql);
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    return $rs_result;
}

// 读取不属于指定项目和设备id下的所有供应商，默认按id倒序排序
function get_supplier_not_for_full_path($PoemDB, $postdata, $order = '')
{
    $project_id = $postdata['project_id'];
    $equipment_id = $postdata['equipment_id'];
    
    if (intval($project_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的项目ID';
        return $rs_result;
    }
    if (intval($equipment_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的设备ID';
        return $rs_result;
    }
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 定义查询语句
    $sql = "SELECT supplier_id, supplier_name
            FROM tb_supplier
            WHERE supplier_id not in
            (
                SELECT supplier_id
                FROM tb_project_equipment_supplier
                WHERE project_id=$project_id AND equipment_id=$equipment_id
            )
            ORDER BY supplier_id DESC";
    
    try {
        $rs = $pdo->query($sql);
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    return $rs_result;
}

// 读取指定id的问题信息
function get_issue_by_id($PoemDB, $postdata)
{
    $issue_id = $postdata['issue_id'];

    if (intval($issue_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的问题ID';
        return $rs_result;
    }

    // 用于返回执行结果的数组
    $rs_result = array();

    // 查询语句初始化
    $sql = '';

    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);

    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }

    // 定义查询语句
    $sql = "select issue_id, issue_desc, issue_voucher_no, issue_voucher_date, issue_causes, issue_solve, issue_type, user_name, last_update, create_date
            from tb_issue
            where issue_id=$issue_id";

    try {
        $rs = $pdo->query($sql);
    } catch (PDOException $e) {
        $arr_tables_list["errmsg"] = $e->getMessage();
        return $arr_tables_list;
    }

    $rs_result = $rs->fetchAll(PDO::FETCH_ASSOC);

    return $rs_result;
}

// 读取指定项目、设备、供应商id以及问题类型下所有问题，默认按id倒序排序
function get_issue_list_by_full_path($PoemDB, $postdata, $order = '', $fromid = '')
{
    $project_id = $postdata['project_id'];
    $equipment_id = $postdata['equipment_id'];
    $supplier_id = $postdata['supplier_id'];
    $issue_type = $postdata['issue_type'];
    
    if (intval($project_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的项目ID';
        return $rs_result;
    }
    if (intval($equipment_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的设备ID';
        return $rs_result;
    }
    if (intval($supplier_id) <= 0) {
        $rs_result["errmsg"] = '请选择正确的供应商ID';
        return $rs_result;
    }
    if (! empty($issue_type) && (intval($issue_type) <= 0)) {
        $rs_result["errmsg"] = '请选择正确的问题类型';
        return $rs_result;
    }
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 定义查询语句
    $sql = "select issue_id, issue_desc, issue_voucher_no, issue_voucher_date, issue_causes, issue_solve, user_name, last_update, create_date, 
        project_id, equipment_id, supplier_id, issue_type
      from tb_issue where ";
    if (! empty($issue_type)) {
        $sql .= " issue_type=$issue_type and ";
    }
    $sql .= " supplier_id=$supplier_id and equipment_id=$equipment_id and project_id=$project_id order by issue_id desc";
    
    try {
        $rs = $pdo->query($sql);
    } catch (PDOException $e) {
        $arr_tables_list["errmsg"] = $e->getMessage();
        return $arr_tables_list;
    }
    
    $rs_result = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    return $rs_result;
}

/*
 * 新建一条项目信息，信息数据在$project_data数组
 * $project_data = array(
 * 'project_name' => '',
 * 'project_desc' => '',
 * 'create_date' => date("Y-m-d H:i:s"),
 * 'user_id' => '',
 * 'user_name' => ''
 * );
 */
function insert_new_project_item($PoemDB, $project_data)
{
    // 用于返回执行结果的数组
    $rs_result = array();
    
    $project_data['project_name'] = trim($project_data['project_name']);
    $project_data['project_desc'] = trim($project_data['project_desc']);
    $project_data['user_id'] = intval($project_data['user_id']);
    $project_data['user_name'] = trim($project_data['user_name']);
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 检查提交的数据是否正确
    if ($project_data['project_name'] == '') {
        $rs_result["errmsg"] = '项目名称不得为空';
        return $rs_result;
    }
    if ($project_data['project_desc'] == '') {
        $rs_result["errmsg"] = '项目描述不得为空';
        return $rs_result;
    }
    if ($project_data['user_id'] <= 0) {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    if ($project_data['user_name'] == '') {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    
    // 定义查询语句
    $sth = $pdo->prepare("insert into tb_project
               ( project_name,  project_desc,  create_date,  user_id,  user_name,  last_update)
        values (:project_name, :project_desc, :create_date, :user_id, :user_name, :create_date) 
        ");
    
    try {
        $sth->bindParam(':project_name', $project_data['project_name'], PDO::PARAM_STR);
        $sth->bindParam(':project_desc', $project_data['project_desc'], PDO::PARAM_STR);
        $sth->bindParam(':create_date', $project_data['create_date'], PDO::PARAM_STR);
        $sth->bindParam(':user_id', $project_data['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':user_name', $project_data['user_name'], PDO::PARAM_STR);
        $sth->execute();
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = array(
        "status" => 'success',
        "optinfo" => '添加项目完成',
        "rows" => $sth->rowCount()
    );
    
    return $rs_result;
}

/*
 * //更新一条项目信息，信息数据在$project_data数组
 * $project_data = array(
 * 'project_id' => '',
 * 'project_name' => '',
 * 'project_desc' => '',
 * 'last_update' => date("Y-m-d H:i:s"),
 * 'user_id' => '',
 * 'user_name' => ''
 * );
 *
 */
function update_project_item($PoemDB, $project_data)
{
    // 用于返回执行结果的数组
    $rs_result = array();
    
    $project_data['project_id'] = intval($project_data['project_id']);
    $project_data['project_name'] = trim($project_data['project_name']);
    $project_data['project_desc'] = trim($project_data['project_desc']);
    $project_data['user_id'] = intval($project_data['user_id']);
    $project_data['user_name'] = trim($project_data['user_name']);
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 检查提交的数据是否正确
    if ($project_data['project_id'] <= 0) {
        $rs_result["errmsg"] = '指定项目不存在';
        return $rs_result;
    }
    if ($project_data['project_name'] == '') {
        $rs_result["errmsg"] = '项目名称不得为空';
        return $rs_result;
    }
    if ($project_data['project_desc'] == '') {
        $rs_result["errmsg"] = '项目描述不得为空';
        return $rs_result;
    }
    if ($project_data['user_id'] <= 0) {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    if ($project_data['user_name'] == '') {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    
    // 定义查询语句
    $sth = $pdo->prepare("update tb_project
           set project_name=:project_name,
               project_desc=:project_desc,
               last_update=:last_update,
               user_id=:user_id,
               user_name=:user_name
         where project_id = :project_id");
    
    try {
        $sth->bindParam(':project_id', $project_data['project_id'], PDO::PARAM_INT);
        $sth->bindParam(':project_name', $project_data['project_name'], PDO::PARAM_STR);
        $sth->bindParam(':project_desc', $project_data['project_desc'], PDO::PARAM_STR);
        $sth->bindParam(':last_update', $project_data['last_update'], PDO::PARAM_STR);
        $sth->bindParam(':user_id', $project_data['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':user_name', $project_data['user_name'], PDO::PARAM_STR);
        $sth->execute();
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = array(
        "status" => 'success',
        "optinfo" => '更新项目数据完成',
        "rows" => $sth->rowCount()
    );
    
    return $rs_result;
}

/*
 * 插入一条设备信息，信息数据在$postdata数组
 * $postdata = array(
 * 'equipment_name' => '',
 * 'equipment_desc' => '',
 * 'equipment_model' => '',
 * 'equipment_spec' => '',
 * 'equipment_param' => '',
 * 'create_date' => date("Y-m-d H:i:s"),
 * 'user_id' => '',
 * 'user_name' => '',
 * 'project_id' => ''
 * );
 */
function insert_new_equipment($PoemDB, $postdata)
{
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    $postdata['equipment_name'] = trim($postdata['equipment_name']);
    $postdata['equipment_desc'] = trim($postdata['equipment_desc']);
    $postdata['equipment_model'] = trim($postdata['equipment_model']);
    $postdata['equipment_spec'] = trim($postdata['equipment_spec']);
    $postdata['equipment_param'] = trim($postdata['equipment_param']);
    $postdata['user_id'] = intval($postdata['user_id']);
    $postdata['user_name'] = trim($postdata['user_name']);
    $postdata['project_id'] = intval($postdata['project_id']);
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 检查提交的数据是否正确
    if ($postdata['equipment_name'] == '') {
        $rs_result["errmsg"] = '设备名称不得为空';
        return $rs_result;
    }
    if ($postdata['equipment_desc'] == '') {
        $rs_result["errmsg"] = '设备描述不得为空';
        return $rs_result;
    }
    if ($postdata['equipment_model'] == '') {
        $rs_result["errmsg"] = '设备型号不得为空';
        return $rs_result;
    }
    if ($postdata['equipment_spec'] == '') {
        $rs_result["errmsg"] = '设备规格不得为空';
        return $rs_result;
    }
    if ($postdata['equipment_param'] == '') {
        $rs_result["errmsg"] = '设备参数不得为空';
        return $rs_result;
    }
    if ($postdata['project_id'] <= 0) {
        $rs_result["errmsg"] = '所属项目信息错误';
        return $rs_result;
    }
    if ($postdata['user_id'] <= 0) {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    if ($postdata['user_name'] == '') {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    
    // 定义查询语句
    // 先插入设备信息表，取得新插入的数据id，再插入项目和设备关联表
    $sth = $pdo->prepare("insert into `tb_equipment`
           ( equipment_name,  equipment_desc,  equipment_model,  equipment_spec,  equipment_param,  create_date,  user_id,  user_name,  last_update)
    values (:equipment_name, :equipment_desc, :equipment_model, :equipment_spec, :equipment_param, :create_date, :user_id, :user_name, :create_date);
           insert into `tb_project_equipment`
           (             equipment_id,  project_id,  create_date,  user_id,  user_name)
    values ((SELECT LAST_INSERT_ID()), :project_id, :create_date, :user_id, :user_name);
    ");
    
    try {
        $sth->bindParam(':equipment_name', $postdata['equipment_name'], PDO::PARAM_STR);
        $sth->bindParam(':equipment_desc', $postdata['equipment_desc'], PDO::PARAM_STR);
        $sth->bindParam(':equipment_model', $postdata['equipment_model'], PDO::PARAM_STR);
        $sth->bindParam(':equipment_spec', $postdata['equipment_spec'], PDO::PARAM_STR);
        $sth->bindParam(':equipment_param', $postdata['equipment_param'], PDO::PARAM_STR);
        $sth->bindParam(':create_date', $postdata['create_date'], PDO::PARAM_STR);
        $sth->bindParam(':user_id', $postdata['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':user_name', $postdata['user_name'], PDO::PARAM_STR);
        $sth->bindParam(':project_id', $postdata['project_id'], PDO::PARAM_INT);
        
        $sth->execute();
        
        // 得到最新插入的数据id
        // $lastId = $pdo->lastInsertId('equipment_id');
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = array(
        "project_id" => $postdata['project_id'],
        "status" => 'success',
        "optinfo" => '新增设备数据完成',
        "rows" => $sth->rowCount()
    );
    
    return $rs_result;
}

/*
 * 更新一条设备信息，信息数据在$postdata数组
 * $postdata = array(
 * 'equipment_id' => '',
 * 'equipment_name' => '',
 * 'equipment_desc' => '',
 * 'equipment_model' => '',
 * 'equipment_spec' => '',
 * 'equipment_param' => '',
 * 'last_update' => date("Y-m-d H:i:s"),
 * 'user_id' => '',
 * 'user_name' => ''
 * );
 */
function update_equipment_item($PoemDB, $postdata)
{
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    $postdata['equipment_id'] = intval($postdata['equipment_id']);
    $postdata['equipment_name'] = trim($postdata['equipment_name']);
    $postdata['equipment_desc'] = trim($postdata['equipment_desc']);
    $postdata['equipment_model'] = trim($postdata['equipment_model']);
    $postdata['equipment_spec'] = trim($postdata['equipment_spec']);
    $postdata['equipment_param'] = trim($postdata['equipment_param']);
    $postdata['user_id'] = intval($postdata['user_id']);
    $postdata['user_name'] = trim($postdata['user_name']);
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 检查提交的数据是否正确
    if ($postdata['equipment_id'] <= 0) {
        $rs_result["errmsg"] = '指定的设备不存在';
        return $rs_result;
    }
    if ($postdata['equipment_name'] == '') {
        $rs_result["errmsg"] = '设备名称不得为空';
        return $rs_result;
    }
    if ($postdata['equipment_desc'] == '') {
        $rs_result["errmsg"] = '设备描述不得为空';
        return $rs_result;
    }
    if ($postdata['equipment_model'] == '') {
        $rs_result["errmsg"] = '设备型号不得为空';
        return $rs_result;
    }
    if ($postdata['equipment_spec'] == '') {
        $rs_result["errmsg"] = '设备规格不得为空';
        return $rs_result;
    }
    if ($postdata['equipment_param'] == '') {
        $rs_result["errmsg"] = '设备参数不得为空';
        return $rs_result;
    }
    if ($postdata['user_id'] <= 0) {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    if ($postdata['user_name'] == '') {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    
    // 定义查询语句
    // 先插入设备信息表，取得新插入的数据id，再插入项目和设备关联表
    $sth = $pdo->prepare("update `tb_equipment`
           set equipment_name=:equipment_name,
               equipment_desc=:equipment_desc,
               equipment_model=:equipment_model,
               equipment_spec=:equipment_spec,
               equipment_param=:equipment_param,
               last_update=:last_update,
               user_id=:user_id,
               user_name=:user_name
         where equipment_id=:equipment_id");
    
    try {
        $sth->bindParam(':equipment_id', $postdata['equipment_id'], PDO::PARAM_INT);
        $sth->bindParam(':equipment_name', $postdata['equipment_name'], PDO::PARAM_STR);
        $sth->bindParam(':equipment_desc', $postdata['equipment_desc'], PDO::PARAM_STR);
        $sth->bindParam(':equipment_model', $postdata['equipment_model'], PDO::PARAM_STR);
        $sth->bindParam(':equipment_spec', $postdata['equipment_spec'], PDO::PARAM_STR);
        $sth->bindParam(':equipment_param', $postdata['equipment_param'], PDO::PARAM_STR);
        $sth->bindParam(':last_update', $postdata['last_update'], PDO::PARAM_STR);
        $sth->bindParam(':user_id', $postdata['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':user_name', $postdata['user_name'], PDO::PARAM_STR);
        
        $sth->execute();
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = array(
        "project_id" => $postdata['project_id'],
        "status" => 'success',
        "optinfo" => '更新设备数据完成',
        "rows" => $sth->rowCount()
    );
    
    return $rs_result;
}

/*
 * 插入一条项目和设备关联信息，信息数据在$postdata数组
 * $postdata = array(
 * 'equipment_id' => ''
 * 'project_id' => ''
 * 'create_date' => date("Y-m-d H:i:s"),
 * 'user_id' => '',
 * 'user_name' => ''
 * );
 */
function insert_new_project_equipment_link($PoemDB, $postdata)
{
    // 用于返回执行结果的数组
    $rs_result = array();
    
    $postdata['equipment_id'] = intval($postdata['equipment_id']);
    $postdata['project_id'] = intval($postdata['project_id']);
    $postdata['user_id'] = intval($postdata['user_id']);
    $postdata['user_name'] = trim($postdata['user_name']);
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 检查提交的数据是否正确
    if ($postdata['equipment_id'] <= 0) {
        $rs_result["errmsg"] = '设备信息错误';
        return $rs_result;
    }
    if ($postdata['project_id'] <= 0) {
        $rs_result["errmsg"] = '所属项目信息错误';
        return $rs_result;
    }
    if ($postdata['user_id'] <= 0) {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    if ($postdata['user_name'] == '') {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    
    // 定义查询语句
    // 先插入设备信息表，取得新插入的数据id，再插入项目和设备关联表
    $sth = $pdo->prepare("insert into `tb_project_equipment`
           ( equipment_id,  project_id,  create_date,  user_id,  user_name)
    values (:equipment_id, :project_id, :create_date, :user_id, :user_name);
    ");
    
    try {
        $sth->bindParam(':equipment_id', $postdata['equipment_id'], PDO::PARAM_INT);
        $sth->bindParam(':project_id', $postdata['project_id'], PDO::PARAM_INT);
        $sth->bindParam(':create_date', $postdata['create_date'], PDO::PARAM_STR);
        $sth->bindParam(':user_id', $postdata['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':user_name', $postdata['user_name'], PDO::PARAM_STR);
        
        $sth->execute();
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = array(
        "project_id" => $postdata['project_id'],
        "status" => 'success',
        "optinfo" => '在该项目下添加设备完成',
        "rows" => $sth->rowCount()
    );
    
    return $rs_result;
}

/*
 * 插入一条供应商信息，信息数据在$postdata数组
 * $postdata = array(
 * 'supplier_name' => '',
 * 'supplier_desc' => '',
 * 'create_date' => date("Y-m-d H:i:s"),
 * 'user_id' => '',
 * 'user_name' => '',
 * 'project_id' => '',
 * 'equipment_id' => ''
 * );
 */
function insert_new_supplier($PoemDB, $postdata)
{
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    $postdata['supplier_name'] = trim($postdata['supplier_name']);
    $postdata['supplier_desc'] = trim($postdata['supplier_desc']);
    $postdata['user_id'] = intval($postdata['user_id']);
    $postdata['user_name'] = trim($postdata['user_name']);
    $postdata['project_id'] = intval($postdata['project_id']);
    $postdata['equipment_id'] = intval($postdata['equipment_id']);
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 检查提交的数据是否正确
    if ($postdata['supplier_name'] == '') {
        $rs_result["errmsg"] = '供应商名称不得为空';
        return $rs_result;
    }
    if ($postdata['supplier_desc'] == '') {
        $rs_result["errmsg"] = '供应商描述不得为空';
        return $rs_result;
    }
    if ($postdata['project_id'] <= 0) {
        $rs_result["errmsg"] = '所属项目信息错误';
        return $rs_result;
    }
    if ($postdata['equipment_id'] <= 0) {
        $rs_result["errmsg"] = '所属设备信息错误';
        return $rs_result;
    }
    
    if ($postdata['user_id'] <= 0) {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    if ($postdata['user_name'] == '') {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    
    // 定义查询语句
    // 先插入供应商信息表，取得新插入的数据id，再插入项目、设备和供应商关联表
    $sth = $pdo->prepare("insert into `tb_supplier`
           ( supplier_name,  supplier_desc,  create_date,  user_id,  user_name,  last_update)
    values (:supplier_name, :supplier_desc, :create_date, :user_id, :user_name, :create_date);
           insert into `tb_project_equipment_supplier`
           (              supplier_id,  equipment_id,  project_id,  create_date,  user_id,  user_name)
    values ((SELECT LAST_INSERT_ID()), :equipment_id, :project_id, :create_date, :user_id, :user_name);
    ");
    
    try {
        $sth->bindParam(':supplier_name', $postdata['supplier_name'], PDO::PARAM_STR);
        $sth->bindParam(':supplier_desc', $postdata['supplier_desc'], PDO::PARAM_STR);
        $sth->bindParam(':create_date', $postdata['create_date'], PDO::PARAM_STR);
        $sth->bindParam(':user_id', $postdata['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':user_name', $postdata['user_name'], PDO::PARAM_STR);
        $sth->bindParam(':project_id', $postdata['project_id'], PDO::PARAM_INT);
        $sth->bindParam(':equipment_id', $postdata['equipment_id'], PDO::PARAM_INT);
        
        $sth->execute();
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = array(
        "project_id" => $postdata['project_id'],
        "equipment_id" => $postdata['equipment_id'],
        "status" => 'success',
        "optinfo" => '新增供应商数据完成',
        "rows" => $sth->rowCount()
    );
    
    return $rs_result;
}

/*
 * 更新一条供应商信息，信息数据在$postdata数组
 * $postdata = array(
 * 'supplier_id' => '',
 * 'supplier_name' => '',
 * 'supplier_desc' => '',
 * 'last_update' => date("Y-m-d H:i:s"),
 * 'user_id' => '',
 * 'user_name' => ''
 * );
 *
 */
function update_supplier_item($PoemDB, $postdata)
{
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    $postdata['supplier_id'] = intval($postdata['supplier_id']);
    $postdata['supplier_name'] = trim($postdata['supplier_name']);
    $postdata['supplier_desc'] = trim($postdata['supplier_desc']);
    $postdata['user_id'] = intval($postdata['user_id']);
    $postdata['user_name'] = trim($postdata['user_name']);
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 检查提交的数据是否正确
    if ($postdata['supplier_id'] <= 0) {
        $rs_result["errmsg"] = '指定的供应商不存在';
        return $rs_result;
    }
    if ($postdata['supplier_name'] == '') {
        $rs_result["errmsg"] = '供应商名称不得为空';
        return $rs_result;
    }
    if ($postdata['supplier_desc'] == '') {
        $rs_result["errmsg"] = '供应商描述不得为空';
        return $rs_result;
    }
    
    if ($postdata['user_id'] <= 0) {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    if ($postdata['user_name'] == '') {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    
    // 定义查询语句
    // 先插入供应商信息表，取得新插入的数据id，再插入项目、设备和供应商关联表
    $sth = $pdo->prepare("update `tb_supplier`
        set supplier_name=:supplier_name,
            supplier_desc=:supplier_desc,
            last_update=:last_update,
            user_id=:user_id,
            user_name=:user_name
      where supplier_id=:supplier_id");
    
    try {
        $sth->bindParam(':supplier_id', $postdata['supplier_id'], PDO::PARAM_INT);
        $sth->bindParam(':supplier_name', $postdata['supplier_name'], PDO::PARAM_STR);
        $sth->bindParam(':supplier_desc', $postdata['supplier_desc'], PDO::PARAM_STR);
        $sth->bindParam(':last_update', $postdata['last_update'], PDO::PARAM_STR);
        $sth->bindParam(':user_id', $postdata['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':user_name', $postdata['user_name'], PDO::PARAM_STR);
        
        $sth->execute();
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = array(
        "project_id" => $postdata['project_id'],
        "equipment_id" => $postdata['equipment_id'],
        "status" => 'success',
        "optinfo" => '更新供应商数据完成',
        "rows" => $sth->rowCount()
    );
    
    return $rs_result;
}

/*
 * 插入一条项目、设备和供应商的关联信息，信息数据在$postdata数组
 * $postdata = array(
 * 'project_id' => '',
 * 'equipment_id' => '',
 * 'supplier_id' => '',
 * 'create_date' => date("Y-m-d H:i:s"),
 * 'user_id' => '',
 * 'user_name' => ''
 * );
 *
 */
function insert_new_project_equipment_supplier($PoemDB, $postdata)
{
    
    // 用于返回执行结果的数组
    $rs_result = array();
    
    $postdata['project_id'] = intval($postdata['project_id']);
    $postdata['equipment_id'] = intval($postdata['equipment_id']);
    $postdata['supplier_id'] = intval($postdata['supplier_id']);
    $postdata['user_id'] = intval($postdata['user_id']);
    $postdata['user_name'] = trim($postdata['user_name']);
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 检查提交的数据是否正确
    if ($postdata['project_id'] <= 0) {
        $rs_result["errmsg"] = '所属项目信息错误';
        return $rs_result;
    }
    if ($postdata['equipment_id'] <= 0) {
        $rs_result["errmsg"] = '所属设备信息错误';
        return $rs_result;
    }
    if ($postdata['supplier_id'] <= 0) {
        $rs_result["errmsg"] = '供应商信息错误';
        return $rs_result;
    }
    if ($postdata['user_id'] <= 0) {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    if ($postdata['user_name'] == '') {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    
    // 定义查询语句
    // 先插入供应商信息表，取得新插入的数据id，再插入项目、设备和供应商关联表
    $sth = $pdo->prepare("insert into `tb_project_equipment_supplier`
           ( supplier_id,  equipment_id,  project_id,  create_date,  user_id,  user_name)
    values (:supplier_id, :equipment_id, :project_id, :create_date, :user_id, :user_name);
    ");
    
    try {
        $sth->bindParam(':project_id', $postdata['project_id'], PDO::PARAM_INT);
        $sth->bindParam(':equipment_id', $postdata['equipment_id'], PDO::PARAM_INT);
        $sth->bindParam(':supplier_id', $postdata['supplier_id'], PDO::PARAM_INT);
        $sth->bindParam(':create_date', $postdata['create_date'], PDO::PARAM_STR);
        $sth->bindParam(':user_id', $postdata['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':user_name', $postdata['user_name'], PDO::PARAM_STR);
        
        $sth->execute();
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = array(
        "project_id" => $postdata['project_id'],
        "equipment_id" => $postdata['equipment_id'],
        "status" => 'success',
        "optinfo" => '在该设备下添加供应商完成',
        "rows" => $sth->rowCount()
    );
    
    return $rs_result;
}

/*
 * 插入一条问题信息，信息数据在$postdata数组
 * $postdata = array(
 * 'issue_desc' => '',
 * 'issue_voucher_no' => '',
 * 'issue_voucher_date' => date("Y-m-d H:i:s"),
 * 'issue_causes' => '',
 * 'issue_solve' => '',
 * 'issue_type' => '',
 * 'supplier_id' => '',
 * 'equipment_id' => '',
 * 'project_id' => '',
 * 'create_time' => date("Y-m-d H:i:s"),
 * 'user_id' => '',
 * 'user_name' => ''
 * );
 */
function insert_new_issue($PoemDB, $postdata)
{
    // 用于返回执行结果的数组
    $rs_result = array();
    
    $postdata['issue_desc'] = trim($postdata['issue_desc']);
    $postdata['issue_voucher_no'] = trim($postdata['issue_voucher_no']);
    $postdata['issue_voucher_date'] = trim($postdata['issue_voucher_date']);
    $postdata['issue_causes'] = trim($postdata['issue_causes']);
    $postdata['issue_solve'] = trim($postdata['issue_solve']);
    $postdata['issue_type'] = intval($postdata['issue_type']);
    $postdata['supplier_id'] = intval($postdata['supplier_id']);
    $postdata['equipment_id'] = intval($postdata['equipment_id']);
    $postdata['project_id'] = intval($postdata['project_id']);
    $postdata['user_id'] = intval($postdata['user_id']);
    $postdata['user_name'] = trim($postdata['user_name']);
    
    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 检查提交的数据是否正确
    if ($postdata['issue_desc'] == '') {
        $rs_result["errmsg"] = '问题描述不得为空';
        return $rs_result;
    }
    if ($postdata['issue_voucher_no'] == '') {
        $rs_result["errmsg"] = '问题所属凭单号不得为空';
        return $rs_result;
    }
    if ($postdata['issue_voucher_date'] == '') {
        $rs_result["errmsg"] = '问题所属凭单日期不得为空';
        return $rs_result;
    }
    if ($postdata['issue_causes'] == '') {
        $rs_result["errmsg"] = '问题成因分析不得为空';
        return $rs_result;
    }
    if ($postdata['issue_solve'] == '') {
        $rs_result["errmsg"] = '问题解决过程不得为空';
        return $rs_result;
    }
    if ($postdata['issue_type'] <= 0) {
        $rs_result["errmsg"] = '问题所属阶段信息错误';
        return $rs_result;
    }
    if ($postdata['supplier_id'] <= 0) {
        $rs_result["errmsg"] = '所属供应商信息错误';
        return $rs_result;
    }
    if ($postdata['equipment_id'] <= 0) {
        $rs_result["errmsg"] = '所属设备信息错误';
        return $rs_result;
    }
    if ($postdata['project_id'] <= 0) {
        $rs_result["errmsg"] = '所属项目信息错误';
        return $rs_result;
    }
    if ($postdata['user_id'] <= 0) {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    if ($postdata['user_name'] == '') {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    
    // 定义查询语句
    // 先插入供应商信息表，取得新插入的数据id，再插入项目、设备和供应商关联表
    $sth = $pdo->prepare("insert into `tb_issue`
           ( issue_desc,  issue_voucher_no,  issue_voucher_date,  issue_causes,  issue_solve,  issue_type,  supplier_id,  equipment_id,  project_id,  create_date,  user_id,  user_name)
    values (:issue_desc, :issue_voucher_no, :issue_voucher_date, :issue_causes, :issue_solve, :issue_type, :supplier_id, :equipment_id, :project_id, :create_date, :user_id, :user_name)
    ");
    
    try {
        $sth->bindParam(':issue_desc', $postdata['issue_desc'], PDO::PARAM_STR);
        $sth->bindParam(':issue_voucher_no', $postdata['issue_voucher_no'], PDO::PARAM_STR);
        $sth->bindParam(':issue_voucher_date', $postdata['issue_voucher_date'], PDO::PARAM_STR);
        $sth->bindParam(':issue_causes', $postdata['issue_causes'], PDO::PARAM_STR);
        $sth->bindParam(':issue_solve', $postdata['issue_solve'], PDO::PARAM_STR);
        $sth->bindParam(':issue_type', $postdata['issue_type'], PDO::PARAM_INT);
        $sth->bindParam(':supplier_id', $postdata['supplier_id'], PDO::PARAM_INT);
        $sth->bindParam(':equipment_id', $postdata['equipment_id'], PDO::PARAM_INT);
        $sth->bindParam(':project_id', $postdata['project_id'], PDO::PARAM_INT);
        $sth->bindParam(':create_date', $postdata['create_date'], PDO::PARAM_STR);
        $sth->bindParam(':user_id', $postdata['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':user_name', $postdata['user_name'], PDO::PARAM_STR);
        
        $sth->execute();
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = array(
        "project_id" => $postdata['project_id'],
        "equipment_id" => $postdata['equipment_id'],
        "supplier_id" => $postdata['supplier_id'],
        "status" => 'success',
        "optinfo" => '添加问题数据完成',
        "rows" => $sth->rowCount()
    );
    
    return $rs_result;
}

/*
 * 更新一条问题信息，信息数据在$postdata数组
 * $postdata = array(
 * 'issue_id' => '',
 * 'issue_desc' => '',
 * 'issue_voucher_no' => '',
 * 'issue_voucher_date' => date("Y-m-d H:i:s"),
 * 'issue_causes' => '',
 * 'issue_solve' => '',
 * 'issue_type' => '',
 * 'last_update' => date("Y-m-d H:i:s"),
 * 'user_id' => '',
 * 'user_name' => ''
 * );
 *
 */
function update_issue_item($PoemDB, $postdata)
{
    // 用于返回执行结果的数组
    $rs_result = array();
    
    $postdata['issue_id'] = intval($postdata['issue_id']);
    $postdata['issue_desc'] = trim($postdata['issue_desc']);
    $postdata['issue_voucher_no'] = trim($postdata['issue_voucher_no']);
    $postdata['issue_voucher_date'] = trim($postdata['issue_voucher_date']);
    $postdata['issue_causes'] = trim($postdata['issue_causes']);
    $postdata['issue_solve'] = trim($postdata['issue_solve']);
    $postdata['issue_type'] = intval($postdata['issue_type']);
    $postdata['user_id'] = intval($postdata['user_id']);
    $postdata['user_name'] = trim($postdata['user_name']);

    // 查询语句初始化
    $sql = '';
    
    // 建立pdo连接
    $pdo = get_db_connect($PoemDB['ip'], $PoemDB['port'], $PoemDB['user'], $PoemDB['pass'], $PoemDB['database']);
    
    if (! is_object($pdo)) {
        if (! empty($pdo['errmsg'])) {
            // 如果连接返回错误，则将错误信息返回
            $rs_result["errmsg"] = $pdo['errmsg'];
            return $rs_result;
        }
    }
    
    // 检查提交的数据是否正确
    if ($postdata['issue_id'] <= 0) {
        $rs_result["errmsg"] = '指定问题不存在';
        return $rs_result;
    }
    if ($postdata['issue_desc'] == '') {
        $rs_result["errmsg"] = '问题描述不得为空';
        return $rs_result;
    }
    if ($postdata['issue_voucher_no'] == '') {
        $rs_result["errmsg"] = '问题所属凭单号不得为空';
        return $rs_result;
    }
    if ($postdata['issue_voucher_date'] == '') {
        $rs_result["errmsg"] = '问题所属凭单日期不得为空';
        return $rs_result;
    }
    if ($postdata['issue_causes'] == '') {
        $rs_result["errmsg"] = '问题成因分析不得为空';
        return $rs_result;
    }
    if ($postdata['issue_solve'] == '') {
        $rs_result["errmsg"] = '问题解决过程不得为空';
        return $rs_result;
    }
    if ($postdata['issue_type'] < 0) {
        $rs_result["errmsg"] = '问题所属阶段信息错误';
        return $rs_result;
    }
    if ($postdata['user_id'] <= 0) {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    if ($postdata['user_name'] == '') {
        $rs_result["errmsg"] = '当前用户登录信息错误，请重新登录';
        return $rs_result;
    }
    
    // 定义查询语句
    // 先插入供应商信息表，取得新插入的数据id，再插入项目、设备和供应商关联表
    $sth = $pdo->prepare("update `tb_issue`
        set issue_desc=:issue_desc,
            issue_voucher_no=:issue_voucher_no,
            issue_voucher_date=:issue_voucher_date,
            issue_causes=:issue_causes,
            issue_type=:issue_type,
            issue_solve=:issue_solve,
            user_id=:user_id,
            user_name=:user_name,
            last_update=:last_update
      where issue_id=:issue_id");
    
    try {
        $sth->bindParam(':issue_id', $postdata['issue_id'], PDO::PARAM_STR);
        $sth->bindParam(':issue_desc', $postdata['issue_desc'], PDO::PARAM_STR);
        $sth->bindParam(':issue_voucher_no', $postdata['issue_voucher_no'], PDO::PARAM_STR);
        $sth->bindParam(':issue_voucher_date', $postdata['issue_voucher_date'], PDO::PARAM_STR);
        $sth->bindParam(':issue_causes', $postdata['issue_causes'], PDO::PARAM_STR);
        $sth->bindParam(':issue_solve', $postdata['issue_solve'], PDO::PARAM_STR);
        $sth->bindParam(':issue_type', $postdata['issue_type'], PDO::PARAM_INT);
        $sth->bindParam(':last_update', $postdata['last_update'], PDO::PARAM_STR);
        $sth->bindParam(':user_id', $postdata['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':user_name', $postdata['user_name'], PDO::PARAM_STR);
        
        $sth->execute();
    } catch (PDOException $e) {
        $rs_result["errmsg"] = $e->getMessage();
        return $rs_result;
    }
    
    $rs_result = array(
        "project_id" => $postdata['project_id'],
        "equipment_id" => $postdata['equipment_id'],
        "supplier_id" => $postdata['supplier_id'],
        "status" => 'success',
        "optinfo" => '更新问题数据完成',
        "rows" => $sth->rowCount()
    );
    
    return $rs_result;
}
?>