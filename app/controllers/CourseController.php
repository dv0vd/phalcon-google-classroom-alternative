<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use Phalcon\Validation;
use Phalcon\Http\Response;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\StringLength\Min;
use Phalcon\Validation\Validator\Confirmation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CourseController extends BaseController {

  public function exportCourseAction($course_id) {
    $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    if(!is_numeric($course_id) || ($course_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!$this -> checkUserPassword() || !$this -> checkUserCourse($course_id)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $user_id = $this -> session -> get('user_id');
    $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
    if($course_user -> role_id == 1) {
      $this -> response -> redirect ('/profile/courses');
    }
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/IComparable.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Spreadsheet.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Calculation/Calculation.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Calculation/Category.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Calculation/Engine/CyclicReferenceStack.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Calculation/Engine/Logger.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/ReferenceHelper.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/Worksheet.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/PageSetup.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/HeaderFooter.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/SheetView.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/Protection.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/Dimension.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/RowDimension.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/AutoFilter.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/CellIterator.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/Iterator.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/RowIterator.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/Row.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/RowCellIterator.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/ColumnDimension.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Worksheet/PageMargins.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Document/Properties.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Document/Security.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Shared/StringHelper.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Shared/XMLWriter.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Collection/CellsFactory.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Collection/Cells.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Collection/Memory.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Settings.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Style/Supervisor.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Style/Font.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Style/Color.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Style/Fill.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Style/Borders.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Style/Border.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Style/Alignment.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Style/NumberFormat.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Style/Protection.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Style/Style.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Cell/Coordinate.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Cell/Cell.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Cell/DataType.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Cell/IValueBinder.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Cell/DefaultValueBinder.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/IWriter.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/BaseWriter.php");
    require_once(APP_PATH . "/libraries/MyCLabs/Enum.php");
    require_once(APP_PATH . "/libraries/ZipStream/Option/Archive.php");
    require_once(APP_PATH . "/libraries/ZipStream/Option/Method.php");
    require_once(APP_PATH . "/libraries/ZipStream/Option/File.php");
    require_once(APP_PATH . "/libraries/ZipStream/Option/Version.php");
    require_once(APP_PATH . "/libraries/ZipStream/ZipStream.php");
    require_once(APP_PATH . "/libraries/ZipStream/Bigint.php");
    require_once(APP_PATH . "/libraries/ZipStream/File.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/WriterPart.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/Comments.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/ContentTypes.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/DocProps.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/Drawing.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/Rels.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/RelsRibbon.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/RelsVBA.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/StringTable.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/Chart.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/Style.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/Theme.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/Workbook.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/Worksheet.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Writer/Xlsx/DefinedNames.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/HashTable.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Calculation/Functions.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Shared/Date.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Shared/Font.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Shared/Drawing.php");
    require_once(APP_PATH . "/libraries/PHPSpreadsheet/Style/NumberFormat/Formatter.php");
    $spreadsheet = new Spreadsheet();
    $writer = new Xlsx($spreadsheet);
    $sheet = $spreadsheet->getActiveSheet();
    $tasks = Tasks::findByCourseId($course_id);
    $course_name = Courses::findFirstById($course_id) -> name;
    if(count($tasks) == 0) {
      $sheet -> setCellValue('A1', 'В курсе нет заданий!');
      $sheet -> getColumnDimension('A')->setAutoSize(true);
      $path = APP_PATH . "/storage/temp/$course_name.xlsx";
      $writer->save($path);
      $name = explode("/", $path);
      $response = new Response();
      $response -> setFileToSend(
        $path,
        $name[7],
        true) -> send();
      unlink($path);
      return;
    }
    $styleArrayCellBorderAll = [
      'borders' => [
          'allBorders' => [
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          ],
      ],
    ];
    $styleArrayCellBorderBottom = [
      'borders' => [
          'bottom' => [
              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
          ],
      ],
    ];
    $styleArrayFontBold = [
      'font' => [
          'bold' => true,
      ],
    ];
    $styleArrayAlignment = [
      'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      ],
    ];
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet -> mergeCells("A1:A4");
    $spreadsheet->getDefaultStyle() -> applyFromArray($styleArrayAlignment);
    $sheet -> setCellValue('A1', 'Фамилия');
    $course_users = CoursesUsers::find(['conditions' => "course_id = $course_id and role_id = 1", 'order' => 'nickname ASC']);
    $i = 5;
    $j = 2;
    foreach($course_users as $course_user) {
      $j = 2;
      $sheet -> setCellValue('A' . $i, $course_user -> nickname);
      $course_user_id = $course_user -> user_id;
      $average_score = 0;
      $total_score = 0;
      for($k = 0; $k < count($tasks); $k++) {
        $task_id = $tasks[$k] -> id;
        $total_score += $tasks[$k] -> score;
        $completed_task = CompletedTasks::findFirst("user_id = $course_user_id and task_id = $task_id");
        if($completed_task == null) {
          $sheet -> getCellByColumnAndRow($j, $i) -> setValue(0);
        } else {
          $sheet -> getCellByColumnAndRow($j, $i) -> setValue($completed_task -> score);
          $average_score += $completed_task -> score;
        }
        $j++;
      }
      $average_score /= $total_score;
      $average_score = round($average_score, 2);
      $sheet -> getCellByColumnAndRow($j, $i) -> setValue($average_score);
      $i++;
    }
    $cell = $sheet -> getCellByColumnAndRow($j, $i - 1);
    $sheet -> getStyle("A1:" . $cell -> getCoordinate()) -> applyFromArray($styleArrayCellBorderAll);
    $i = 2;
    foreach($tasks as $task) {
      $sheet->getColumnDimensionByColumn($i) -> setAutoSize(true);

      $sheet -> getCellByColumnAndRow($i, 2) -> setValue($task -> title);
      $sheet -> getCellByColumnAndRow($i, 4) -> setValue($task -> score);
      $sheet -> getCellByColumnAndRow($i, 4) -> getStyle() -> applyFromArray($styleArrayCellBorderBottom);
      $i++;
    }
    $cell = $spreadsheet -> getActiveSheet() -> getCellByColumnAndRow($i,1);
    $range1 = $cell -> getCoordinate();
    $cell = $spreadsheet -> getActiveSheet() -> getCellByColumnAndRow($i,4);
    $range2 = $cell -> getCoordinate();
    $sheet -> mergeCells("$range1:$range2");
    $sheet -> setCellValue($range1, "Средний балл");
    $sheet -> getStyle($range1) -> applyFromArray($styleArrayFontBold);
    $sheet -> getStyle($range2) -> applyFromArray($styleArrayCellBorderBottom);
    $cell = $spreadsheet -> getActiveSheet() -> getCellByColumnAndRow(count($tasks) + 1,1);
    $range1 = "B1";
    $range2 = $cell -> getCoordinate();
    $sheet -> setCellValue('B1', "Название задания");
    $sheet->getColumnDimension("B") -> setAutoSize(true);
    $sheet -> mergeCells("$range1:$range2");
    $range1 = "B3";
    $cell = $spreadsheet -> getActiveSheet() -> getCellByColumnAndRow(count($tasks) + 1,3);
    $range2 = $cell -> getCoordinate();
    $sheet->getColumnDimensionByColumn(count($tasks) + 2) -> setAutoSize(true);
    $sheet -> mergeCells("$range1:$range2");
    $sheet -> getStyle('A1') -> applyFromArray($styleArrayFontBold);
    $sheet -> getStyle('A4') -> applyFromArray($styleArrayCellBorderBottom);
    $sheet -> getStyle('B1') -> applyFromArray($styleArrayFontBold);
    $sheet -> getStyle('B3') -> applyFromArray($styleArrayFontBold);
    $sheet -> setCellValue('B3', "Максимальный балл");
    $path = APP_PATH . "/storage/temp/$course_name.xlsx";
    $writer->save($path);
    $name = explode("/", $path);
    $response = new Response();
    $response -> setFileToSend(
      $path,
      $name[7],
      true) -> send();
    unlink($path);
    return;
  }

  public function removeUploadedFileAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this ->security -> checkToken()) {
      $result = $this -> getToken();
      $messages = $this -> validation(9);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $file_id = $request -> getPost('fileId');
      $materialFile = MaterialsFiles::findFirstById($file_id);
      if($materialFile == null) {
        $message = ['result' => 'error', 'message' => 'Файл уже не существует!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $materialFile -> delete();
      unlink($materialFile -> location);
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function removeUploadedFileTaskAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this ->security -> checkToken()) {
      $result = $this -> getToken();
      $messages = $this -> validation(9);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $file_id = $request -> getPost('fileId');
      $taskFile = TasksFiles::findFirstById($file_id);
      if($taskFile == null) {
        $message = ['result' => 'error', 'message' => 'Файл уже не существует!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $taskFile -> delete();
      unlink($taskFile -> location);
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function editMaterialAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this ->security -> checkToken()) {
      $result = $this -> getToken();
      $messages = $this -> validation(8);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $messages = $this -> validation(7);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      if($request -> hasFiles()) {
        $uploads = $request -> getUploadedFiles();
        foreach($uploads as $upload) {
          if($upload -> getSize() > 8388608) {
            $message = ['result' => 'error', 'message' => 'Размер каждого файла не может превышать 8 Мб!'];
            array_push($result, $message);
            return json_encode($result);
          }
        }
      }
      $material_id = $request -> getPost('materialId');
      $material = Materials::findFirstById($material_id);
      if($material == null) {
        $message = ['result' => 'error', 'message' => 'Материал уже не существует!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $material -> title = $request -> getPost('editMaterialTitle');
      $material -> description = $request -> getPost('editMaterialDescription');
      $material -> course_id = $course_id;
      $material -> update();
      if($request -> hasFiles()) {
        $uploads = $request -> getUploadedFiles();
        foreach($uploads as $upload) {
          $hash = hash('sha256', time() . rand());
          $path = APP_PATH . '/storage/materials/' . $hash . '__-__' . $upload -> getName() ;
          $upload -> moveTo($path);
          $materialsfiles = new MaterialsFiles();
          $materialsfiles -> material_id = $material -> id;
          $materialsfiles -> location = $path;
          $materialsfiles -> save();
        }
      }
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function editTaskAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken()) {
      $result = $this -> getToken();
      $messages = $this -> validation(12);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $messages = $this -> validation(11);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      if($request -> hasFiles()) {
        $uploads = $request -> getUploadedFiles();
        foreach($uploads as $upload) {
          if($upload -> getSize() > 8388608) {
            $message = ['result' => 'error', 'message' => 'Размер каждого файла не может превышать 8 Мб!'];
            array_push($result, $message);
            return json_encode($result);
          }
        }
      }
      $task_id = $request -> getPost('taskId');
      $task = Tasks::findFirstById($task_id);
      if($task == null) {
        $message = ['result' => 'error', 'message' => 'Задания уже не существует!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $task -> title = $request -> getPost('editTaskTitle');
      $task -> description = $request -> getPost('editTaskDescription');
      $task -> course_id = $course_id;
      $task -> update();
      if($request -> hasFiles()) {
        $uploads = $request -> getUploadedFiles();
        foreach($uploads as $upload) {
          $hash = hash('sha256', time() . rand());
          $path = APP_PATH . '/storage/tasks/' . $hash . '__-__' . $upload -> getName() ;
          $upload -> moveTo($path);
          $tasksfiles = new TasksFiles();
          $tasksfiles -> task_id = $task -> id;
          $tasksfiles -> location = $path;
          $tasksfiles -> save();
        }
      }
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function getMaterialDataAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this ->security -> checkToken()) {
      $result = $this -> getToken();
      $messages = $this -> validation(7);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $material_id = $request -> getPost('materialId');
      $material = Materials::findFirstById($material_id);
      $material_files = MaterialsFiles::findByMaterialId($material_id);
      $message = ['result' => 'success', 'data' => $material, 'files' => $material_files];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function getTaskDataAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this ->security -> checkToken()) {
      $result = $this -> getToken();
      $messages = $this -> validation(11);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $task_id = $request -> getPost('taskId');
      $task = Tasks::findFirstById($task_id);
      $task_files = TasksFiles::findByTaskId($task_id);
      $message = ['result' => 'success', 'data' => $task, 'files' => $task_files];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function materialFileAction($course_id, $material_id, $file_id) {
    $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    if(!is_numeric($course_id) || ($course_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!is_numeric($material_id) || ($material_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!is_numeric($file_id) || ($file_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!$this -> checkUserPassword() || !$this -> checkUserCourse($course_id)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $material = Materials::findFirst("course_id = $course_id and id = $material_id");
    if($material == null) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $materialsFiles = MaterialsFiles::findByMaterialId($material_id);
    foreach($materialsFiles as $materialFile) {
      if($materialFile -> id == $file_id) {
        $name = explode("__-__", $materialFile -> location);
        $response = new Response();
        $response -> setFileToSend(
          $materialFile -> location,
          $name[1],
          true) -> send();
          return;
      }
    }
    $this -> response -> redirect ('/profile/courses');
    return;
  }

  public function taskFileAction($course_id, $task_id, $file_id) {
    $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    if(!is_numeric($course_id) || ($course_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!is_numeric($task_id) || ($task_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!is_numeric($file_id) || ($file_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!$this -> checkUserPassword() || !$this -> checkUserCourse($course_id)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $task = Tasks::findFirst("course_id = $course_id and id = $task_id");
    if($task == null) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $tasksFiles = TasksFiles::findByTaskId($task_id);
    foreach($tasksFiles as $taskFile) {
      if($taskFile -> id == $file_id) {
        $name = explode("__-__", $taskFile -> location);
        $response = new Response();
        $response -> setFileToSend(
          $taskFile -> location,
          $name[1],
          true) -> send();
          return;
      }
    }
    $this -> response -> redirect ('/profile/courses');
    return;
  }

  public function downloadUserFileAction($course_id, $task_id, $user_id, $file_id) {
    $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    if(!is_numeric($course_id) || ($course_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!is_numeric($task_id) || ($task_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!is_numeric($file_id) || ($file_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!is_numeric($user_id) || ($user_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!$this -> checkUserPassword() || !$this -> checkUserCourse($course_id)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $task = Tasks::findFirst("course_id = $course_id and id = $task_id");
    if($task == null) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $userFiles = UsersFiles::find("task_id = $task_id and user_id = $user_id");
    foreach($userFiles as $userFile) {
      if($userFile -> id == $file_id) {
        $name = explode("__-__", $userFile -> location);
        $response = new Response();
        $response -> setFileToSend(
          $userFile -> location,
          $name[1],
          true) -> send();
          return;
      }
    }
    $this -> response -> redirect ('/profile/courses');
    return;
  }

  public function addMaterialAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() ) {
      $result = $this -> getToken();
      $messages = $this -> validation(6);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      if($request -> hasFiles()) {
        $uploads = $request -> getUploadedFiles();
        foreach($uploads as $upload) {
          if($upload -> getSize() > 8388608) {
            $message = ['result' => 'error', 'message' => 'Размер каждого файла не может превышать 8 Мб!'];
            array_push($result, $message);
            return json_encode($result);
          }
        }
      }
      $material = new Materials();
      $material -> title = $request -> getPost('materialTitle');
      $material -> description = $request -> getPost('materialDescription');
      $material -> date_time = date('Y-m-d H:i:s', time());
      $material -> course_id = $course_id;
      $material -> save();
      if($request -> hasFiles()) {
        $uploads = $request -> getUploadedFiles();
        foreach($uploads as $upload) {
          $hash = hash('sha256', time() . rand());
          $path = APP_PATH . '/storage/materials/' . $hash . '__-__' . $upload -> getName() ;
          $upload -> moveTo($path);
          $materialsfiles = new MaterialsFiles();
          $materialsfiles -> material_id = $material -> id;
          $materialsfiles -> location = $path;
          $materialsfiles -> save();
        }
      }
      $courses_users = CoursesUsers::find("course_id = $course_id");
      foreach($courses_users as $course_user) {
        $user_id = $course_user -> user_id;
        $newMaterialsNotifications = NewMaterialsNotifications::findFirst("course_id = $course_id and user_id = $user_id");
        if($newMaterialsNotifications == null) {
          $newMaterialsNotifications = new NewMaterialsNotifications();
          $newMaterialsNotifications -> course_id = $course_id;
          $newMaterialsNotifications -> user_id = $user_id;
          $newMaterialsNotifications -> save();
        }
      }
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function addTaskAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() ) {
      $result = $this -> getToken();
      $messages = $this -> validation(10);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      if($request -> hasFiles()) {
        $uploads = $request -> getUploadedFiles();
        foreach($uploads as $upload) {
          if($upload -> getSize() > 8388608) {
            $message = ['result' => 'error', 'message' => 'Размер каждого файла не может превышать 8 Мб!'];
            array_push($result, $message);
            return json_encode($result);
          }
        }
      }
      $task = new Tasks();
      $task -> title = $request -> getPost('taskTitle');
      $task -> description = $request -> getPost('taskDescription');
      $task -> date_time = date('Y-m-d H:i:s', time());
      $task -> course_id = $course_id;
      $task -> score = $request -> getPost('taskScore');;
      $task -> save();
      if($request -> hasFiles()) {
        $uploads = $request -> getUploadedFiles();
        foreach($uploads as $upload) {
          $hash = hash('sha256', time() . rand());
          $path = APP_PATH . '/storage/tasks/' . $hash . '__-__' . $upload -> getName() ;
          $upload -> moveTo($path);
          $taskfile = new TasksFiles();
          $taskfile -> task_id = $task -> id;
          $taskfile -> location = $path;
          $taskfile -> save();
        }
      }
      $courses_users = CoursesUsers::find("course_id = $course_id");
      foreach($courses_users as $course_user) {
        $user_id = $course_user -> user_id;
        $newTasksNotifications = NewTasksNotifications::findFirst("course_id = $course_id and user_id = $user_id");
        if($newTasksNotifications == null) {
          $newTasksNotifications = new NewTasksNotifications();
          $newTasksNotifications -> course_id = $course_id;
          $newTasksNotifications -> user_id = $user_id;
          $newTasksNotifications -> save();
        }
      }
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function uploadFilesForTaskAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      // $messages = $this -> validation(10);
      // if (count($messages)) {
      //   foreach ($messages as $message) {
      //     array_push($result, $message);
      //   }
      //   return json_encode($result);
      // }
      $course_id = $request -> getPost('courseId');
      $task_id = $request -> getPost('taskId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role != 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      if($request -> hasFiles()) {
        $uploads = $request -> getUploadedFiles();
        foreach($uploads as $upload) {
          if($upload -> getSize() > 8388608) {
            $message = ['result' => 'error', 'message' => 'Размер каждого файла не может превышать 8 Мб!'];
            array_push($result, $message);
            return json_encode($result);
          }
        }
      }
      if($request -> hasFiles()) {
        $uploads = $request -> getUploadedFiles();
        foreach($uploads as $upload) {
          $hash = hash('sha256', time() . rand());
          $path = APP_PATH . '/storage/users_files/' . $hash . '__-__' . $upload -> getName() ;
          $upload -> moveTo($path);
          $user_file = new UsersFiles();
          $user_file -> task_id = $task_id;
          $user_file -> user_id = $user_id;
          $user_file -> location = $path;
          $user_file -> save();
        }
      }
      $nickname = $course_user -> nickname;
      $owner = CoursesUsers::findFirstByRoleId('3');
      $mes = new Messages();
      $mes -> text = "$nickname отправил(а) файлы на проверку";
      $mes-> date_time = date('Y-m-d H:i:s', time());
      $mes -> task_id = $task_id;
      $mes -> sender_id = $user_id;
      $mes -> receptionist_id = $owner -> user_id;
      $mes -> save();
      $receptionist_id = $owner -> user_id;
      $new_message_notification = NewMessagesNotifications::findFirst("task_id = $task_id and receptionist_id = $receptionist_id");
      if($new_message_notification == null) {
        $new_message_notification = new NewMessagesNotifications();
        $new_message_notification -> task_id = $task_id;
        $new_message_notification -> sender_id = $user_id;
        $new_message_notification -> receptionist_id = $receptionist_id;
        $new_message_notification -> save();
      }
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function materialAction($course_id, $material_id) {
    if(!is_numeric($course_id) || ($course_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!is_numeric($material_id) || ($material_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!$this -> checkUserPassword() || !$this -> checkUserCourse($course_id)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $material = Materials::findFirst("course_id = $course_id and id = $material_id");
    if($material == null) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $this -> view -> title = $material -> title;
    $this -> view -> description = $material -> title;
    $this -> view -> material = $material;
    $course = Courses::findFirstById($course_id);
    $user_id = $this -> session -> get('user_id');
    $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
    $this -> view -> role = Roles::findFirstById($course_user -> role_id);
    $this -> view -> course = $course;
    $materialFiles = MaterialsFiles::findByMaterialId($material_id);
    $this -> view -> materialFiles = $materialFiles;
  }

  public function taskAction($course_id, $task_id) {
    if(!is_numeric($course_id) || ($course_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!is_numeric($task_id) || ($task_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!$this -> checkUserPassword() || !$this -> checkUserCourse($course_id)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $task = Tasks::findFirst("course_id = $course_id and id = $task_id");
    if($task == null) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $this -> view -> title = $task -> title;
    $this -> view -> description = $task -> title;
    $this -> view -> task = $task;
    $course = Courses::findFirstById($course_id);
    $user_id = $this -> session -> get('user_id');
    $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
    $user_role = Roles::findFirstById($course_user -> role_id);
    $this -> view -> role = $user_role;
    $this -> view -> course = $course;
    $taskFiles = TasksFiles::findByTaskId($task_id);
    $this -> view -> taskFiles = $taskFiles;
    $this -> view -> usersFiles = UsersFiles::find("user_id = $user_id and task_id = $task_id");
    if($user_role -> role == 'user') {
      $new_message_notification = NewMessagesNotifications::findFirst("task_id = $task_id and receptionist_id = $user_id");
      if($new_message_notification != null) {
        $new_message_notification -> delete();
      }
    }
  }

  public function changeMemberRoleAction(){
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() ) {
      $result = $this -> getToken();
      $messages = $this -> validation(5);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $course_user_id = $request -> getPost('courseUserId');
      $course_user = CoursesUsers::findFirstById($course_user_id);
      $newRole = $request -> getPost('newRole');
      if($newRole == 'owner' && $role -> role != 'owner') {
        $message = ['result' => 'error', 'message' => 'Вы не можете изменить владельца!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $role = Roles::findFirstByRole($newRole);
      if($role == null) {
        $message = ['result' => 'error', 'message' => 'Отсутствует роль!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $course_user -> role_id = $role -> id;
      $course_user -> update();
      if($newRole == 'owner') {
        $user_id = $this -> session -> get('user_id');
        $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
        $course_user -> role_id = 2;
        $course_user -> update();
      }
      if($newRole == 'user') {
        $target_user = $course_user -> user_id;
        $newMembersNotification = NewMembersNotifications::findFirst("user_id = $target_user and course_id = $course_id");
        if($newMembersNotification != null) {
          $newMembersNotification -> delete();
        }
      }
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function removeMemberAction(){
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() ) {
      $result = $this -> getToken();
      $messages = $this -> validation(4);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $course_user_id = $request -> getPost('courseUserId');
      $course_user = CoursesUsers::findFirstById($course_user_id);
      if($course_user == null) {
        $message = ['result' => 'error', 'message' => 'Пользователь уже удалён!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $course_user_role_id = $course_user -> role_id;
      $course_user_role_name = Roles::findFirstById($course_user_role_id);
      if($course_user_role_name -> role == 'owner') {
        $message = ['result' => 'error', 'message' => 'Удалить владельца нельзя!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $course_user -> delete();
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function removeMaterialAction(){
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() ) {
      $result = $this -> getToken();
      $messages = $this -> validation(11);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $task_id = $request -> getPost('taskId');
      $task = Tasks::findFirstById($task_id);
      if($task == null) {
        $message = ['result' => 'error', 'message' => 'Задание уже удалено!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $tasksFiles =TasksFiles::findByTaskId($task_id);
      foreach($tasksFiles as $taskFile) {
        unlink($taskFile -> location);
      }
      $task -> delete();
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function removeTaskAction(){
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() ) {
      $result = $this -> getToken();
      $messages = $this -> validation(7);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $material_id = $request -> getPost('materialId');
      $material = Materials::findFirstById($material_id);
      if($material == null) {
        $message = ['result' => 'error', 'message' => 'Материал уже удалён!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $materialsFiles = MaterialsFiles::findByMaterialId($material_id);
      foreach($materialsFiles as $materialFile) {
        unlink($materialFile -> location);
      }
      $material -> delete();
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function returnUsersFilesAction(){
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      // $messages = $this -> validation(7);
      // if (count($messages)) {
      //   foreach ($messages as $message) {
      //     array_push($result, $message);
      //   }
      //   return json_encode($result);
      // }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role != 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $task_id = $request -> getPost('taskId');
      $user_files = UsersFiles::find("task_id = $task_id and $user_id = $user_id");
      if($user_files == null) {
        $message = ['result' => 'error', 'message' => 'Файлы уже удалены!'];
        array_push($result, $message);
        return json_encode($result);
      }
      foreach($user_files as $user_file) {
        unlink($user_file -> location);
        $user_file -> delete();
      }
      $nickname = $course_user -> nickname;
      $mes = new Messages();
      $owner = CoursesUsers::findFirstByRoleId('3');
      $mes -> text = "$nickname отменил(а) отправку файлов на проверку";
      $mes-> date_time = date('Y-m-d H:i:s', time());
      $mes -> task_id = $task_id;
      $mes -> sender_id = $user_id;
      $mes -> receptionist_id = $owner -> user_id;
      $mes -> save();
      $receptionist_id = $owner -> user_id;
      $new_message_notification = NewMessagesNotifications::findFirst("task_id = $task_id and receptionist_id = $receptionist_id");
      if($new_message_notification == null) {
        $new_message_notification = new NewMessagesNotifications();
        $new_message_notification -> task_id = $task_id;
        $new_message_notification -> sender_id = $user_id;
        $new_message_notification -> receptionist_id = $receptionist_id;
        $new_message_notification -> save();
      }
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function clearNewMembersNotificationsAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $messages = $this -> validation(1);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $newMembersNotifications = NewMembersNotifications::findFirst("course_id = $course_id and user_id = $user_id");
      if($newMembersNotifications != null)
        $newMembersNotifications-> delete();
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function clearNewMessagesNotificationsAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      // $messages = $this -> validation(1);
      // if (count($messages)) {
      //   foreach ($messages as $message) {
      //     array_push($result, $message);
      //   }
      //   return json_encode($result);
      // }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $task_id = $request -> getPost('taskId');
      $user_id = $request -> getPost('userId');
      $newMessagesNotifications = NewMessagesNotifications::findFirst("task_id = $task_id and sender_id = $user_id");
      if($newMessagesNotifications != null)
        $newMessagesNotifications-> delete();
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function clearNewMessagesNotificationsUserAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      // $messages = $this -> validation(1);
      // if (count($messages)) {
      //   foreach ($messages as $message) {
      //     array_push($result, $message);
      //   }
      //   return json_encode($result);
      // }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role != 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $task_id = $request -> getPost('taskId');
      $user_id = $this -> session -> get('user_id');
      $newMessagesNotifications = NewMessagesNotifications::findFirst("task_id = $task_id and receptionist_id = $user_id");
      if($newMessagesNotifications != null)
        $newMessagesNotifications-> delete();
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function clearNewMaterialsNotificationsAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $messages = $this -> validation(1);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $newMaterialsNotifications = NewMaterialsNotifications::findFirst("course_id = $course_id and user_id = $user_id");
      if($newMaterialsNotifications != null)
        $newMaterialsNotifications-> delete();
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function clearNewTasksNotificationsAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $messages = $this -> validation(1);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $newTasksNotifications = NewTasksNotifications::findFirst("course_id = $course_id and user_id = $user_id");
      if($newTasksNotifications != null)
        $newTasksNotifications-> delete();
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function changePasswordAction() {
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken()) {
      $result = $this -> getToken();
      $messages = $this -> validation(3);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role != 'owner') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав на изменение пароля курса!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $course = Courses::findFirstById($course_id);
      $course -> password = $this -> security -> hash($request -> getPost('newPassword'));
      $course -> update();
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function removeAction() {
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken()) {
      $result = $this -> getToken();
      $messages = $this -> validation(1);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role != 'owner') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав на удаление курса!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $course = Courses::findFirstById($course_id);
      $course -> delete();
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function changeCourseNameAction() {
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken()) {
      $result = $this -> getToken();
      $messages = $this -> validation(2);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role != 'owner') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав на изменение имени курса!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $course = Courses::findFirstById($course_id);
      $course -> name = $request -> getPost('courseName');
      $course -> update();
      $message = ['result' => 'success', 'courseName' => $course -> name];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function leaveCourseAction() {
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $request = $this -> request;
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $messages = $this -> validation(1);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'owner') {
        $message = ['result' => 'error', 'message' => 'Вы не можете покинуть курс, так как являетесь его владельцем. Передайте права владения другому участнику и повторите попытку!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $course_user -> delete();
      $message = ['result' => 'success', 'redirect' => '/profile/courses'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function courseAction($course_id) {
    if(!is_numeric($course_id) || ($course_id <= 0)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if(!$this -> checkUserPassword() || !$this -> checkUserCourse($course_id)) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    $course = Courses::findFirstById($course_id);
    $user_id = $this -> session -> get('user_id');
    $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
    $this -> view -> role = Roles::findFirstById($course_user -> role_id);
    $this -> view -> course = $course;
    $this -> view -> course_user = $course_user;
    $course_name = $course -> name;
    $this -> view -> title = $course_name;
    $this -> view -> description = $course_name;
  }

  public function changeNicknameAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax() && $this -> security -> checkToken() ) {
      $result = $this -> getToken();
      $messages = $this -> validation(0);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $course_user -> nickname = $request -> getPost('nickname');
      $course_user -> update();
      $message = ['result' => 'success', 'nickname' => $course_user -> nickname];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  private function validation($type) {
    $validation = new Validation();
    switch($type) {
      case 0:
        $validation -> add(
          'nickname', new PresenceOf([
              'message' => 'Введите новое отображаемое имя!',
          ])
        );
        $validation -> add(
          'courseId', new PresenceOf([
              'message' => 'Отсутствует код курса!',
          ])
        );
        $validation -> add(
          'courseId', new Digit([
              'message' => 'Код курса - положительное число!!',
          ])
        );
        break;
      case 1:
        $validation -> add(
          'courseId', new PresenceOf([
              'message' => 'Отсутствует код курса!',
          ])
        );
        $validation -> add(
          'courseId', new Digit([
              'message' => 'Код курса - положительное число!!',
          ])
        );
        break;
      case 2:
        $validation -> add(
          'courseName', new PresenceOf([
              'message' => 'Отсутствует название курса!',
          ])
        );
        $validation -> add(
          'courseId', new Digit([
              'message' => 'Код курса - положительное число!',
          ])
        );
        break;
      case 3:
        $validation -> add(
          'courseId', new Digit([
              'message' => 'Код курса - положительное число!',
          ])
        );
        $validation -> add(
          'newPassword', new PresenceOf([
              'message' => 'Введите новый пароль!',
          ])
        );
        $validation -> add(
          'confirmPassword', new PresenceOf([
              'message' => 'Введите подтверждение пароля!',
          ])
        );
        $validation -> add(
          "newPassword", new Min([
              "min"     => 8,
              "message" => "Длина нового пароля должна быть минимум 8 символов!",
              "included" => false
          ])
        );
        $validation -> add(
          "confirmPassword", new Min([
              "min"     => 8,
              "message" => "Длина подтверждения пароля должна быть минимум 8 символов!",
              "included" => false
          ])
        );
        $validation -> add(
          "newPassword", new Confirmation([
              "message" => "Пароли не совпадают!",
              "with"    => "confirmPassword",
          ])
        );
        break;
      case 4:
        $validation -> add(
          'courseUserId', new PresenceOf([
              'message' => 'Отсутствует код записи!',
          ])
        );
        $validation -> add(
          'courseId', new PresenceOf([
              'message' => 'Отсутствует код курса!',
          ])
        );
        $validation -> add(
          'courseId', new Digit([
              'message' => 'Код курса - положительное число!',
          ])
        );
        $validation -> add(
          'courseUserId', new Digit([
              'message' => 'Код записи - положительное число!',
          ])
        );
        break;
      case 5:
        $validation -> add(
          'courseUserId', new PresenceOf([
              'message' => 'Отсутствует код записи!',
          ])
        );
        $validation -> add(
          'courseId', new PresenceOf([
              'message' => 'Отсутствует код курса!',
          ])
        );
        $validation -> add(
          'newRole', new PresenceOf([
              'message' => 'Отсутствует роль!',
          ])
        );
        $validation -> add(
          'courseId', new Digit([
              'message' => 'Код курса - положительное число!',
          ])
        );
        $validation -> add(
          'courseUserId', new Digit([
              'message' => 'Код записи - положительное число!',
          ])
        );
        break;
      case 6:
        $validation -> add(
          'courseId', new PresenceOf([
              'message' => 'Отсутствует код курса!',
          ])
        );
        $validation -> add(
          'materialTitle', new PresenceOf([
              'message' => 'Отсутствует название материала!',
          ])
        );
        $validation -> add(
          'materialDescription', new PresenceOf([
              'message' => 'Отсутствует описание материала!',
          ])
        );
        $validation -> add(
          'courseId', new Digit([
              'message' => 'Код курса - положительное число!',
          ])
        );
        break;
      case 7:
        $validation -> add(
          'courseId', new PresenceOf([
              'message' => 'Отсутствует код курса!',
          ])
        );
        $validation -> add(
          'materialId', new PresenceOf([
              'message' => 'Отсутствует код материала!',
          ])
        );
        $validation -> add(
          'courseId', new Digit([
              'message' => 'Код курса - положительное число!',
          ])
        );
        $validation -> add(
          'materialId', new Digit([
              'message' => 'Код материала - положительное число!',
          ])
        );
        break;
      case 8:
        $validation -> add(
          'editMaterialTitle', new PresenceOf([
              'message' => 'Отсутствует название материала!',
          ])
        );
        $validation -> add(
          'editMaterialDescription', new PresenceOf([
              'message' => 'Отсутствует описание материала!',
          ])
        );
        break;
      case 9:
        $validation -> add(
          'fileId', new PresenceOf([
              'message' => 'Отсутствует код файла!',
          ])
        );
        $validation -> add(
          'fileId', new Digit([
              'message' => 'Код файла - положительное число!',
          ])
        );
        $validation -> add(
          'courseId', new PresenceOf([
              'message' => 'Отсутствует код курса!',
          ])
        );
        $validation -> add(
          'courseId', new Digit([
              'message' => 'Код курса - положительное число!',
          ])
        );
        break;
      case 10:
        $validation -> add(
          'courseId', new PresenceOf([
              'message' => 'Отсутствует код курса!',
          ])
        );
        $validation -> add(
          'taskTitle', new PresenceOf([
              'message' => 'Отсутствует название задания!',
          ])
        );
        $validation -> add(
          'taskDescription', new PresenceOf([
              'message' => 'Отсутствует описание задания!',
          ])
        );
        $validation -> add(
          'taskScore', new PresenceOf([
              'message' => 'Отсутствует максимальный балл задания!',
          ])
        );
        $validation -> add(
          'courseId', new Digit([
              'message' => 'Код курса - положительное число!',
          ])
        );
        $validation -> add(
          'taskScore', new Digit([
              'message' => 'Максимальный балл задания - положительное число!',
          ])
        );
        break;
      case 11:
        $validation -> add(
          'courseId', new PresenceOf([
              'message' => 'Отсутствует код курса!',
          ])
        );
        $validation -> add(
          'taskId', new PresenceOf([
              'message' => 'Отсутствует код задания!',
          ])
        );
        $validation -> add(
          'courseId', new Digit([
              'message' => 'Код курса - положительное число!',
          ])
        );
        $validation -> add(
          'taskId', new Digit([
              'message' => 'Код задания - положительное число!',
          ])
        );
        break;
      case 12:
        $validation -> add(
          'editTaskTitle', new PresenceOf([
              'message' => 'Отсутствует название задания!',
          ])
        );
        $validation -> add(
          'editTaskDescription', new PresenceOf([
              'message' => 'Отсутствует описание задания!',
          ])
        );
        break;
      case 13:
        $validation -> add(
          'taskMessage', new PresenceOf([
              'message' => 'Отсутствует сообщение!',
          ])
        );
        break;
    }
    return $validation -> validate($_POST);
  }

  public function getMembersAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $messages = $this -> validation(1);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      if($role -> role == 'user') {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $course_members = CoursesUsers::find("course_id = $course_id and user_id != $user_id");
      $newMembersNotifications = NewMembersNotifications::findFirst("course_id = $course_id and user_id = $user_id");
      $users = Users::find();
      if($newMembersNotifications != null) {
        $message = ['result' => 'success', 'data' => $course_members, 'notification' => true, 'users' => $users];
      } else {
        $message = ['result' => 'success', 'data' => $course_members, 'notification' => false, 'users' => $users];
      }
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }

  }

  public function getMaterialsAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $messages = $this -> validation(1);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      $newMaterialsNotifications = NewMaterialsNotifications::findFirst("course_id = $course_id and user_id = $user_id");
      $materials = Materials::find(['conditions' => "course_id = $course_id", 'order' => 'date_time DESC']);
      if($newMaterialsNotifications != null) {
        $message = ['result' => 'success', 'data' => $materials, 'notification' => true];
      } else {
        $message = ['result' => 'success', 'data' => $materials, 'notification' => false];
      }
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function getTasksAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $messages = $this -> validation(1);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $role = Roles::findFirstById($course_user -> role_id);
      $newTasksNotifications = NewTasksNotifications::findFirst("course_id = $course_id and user_id = $user_id");
      $tasks = Tasks::find(['conditions' => "course_id = $course_id", 'order' => 'date_time DESC']);
      $completed_tasks = CompletedTasks::find("user_id = $user_id");
      $owner_id = CoursesUsers::findFirst("course_id = $course_id and role_id = 3") -> user_id;
      if($course_user -> role_id == 1) {
        $new_messages_notifications = NewMessagesNotifications::find("receptionist_id = $user_id");
      } else {
        $new_messages_notifications = NewMessagesNotifications::find("receptionist_id = $owner_id");
      }
      if($newTasksNotifications != null || count($new_messages_notifications) != 0) {
        $message = ['result' => 'success', 'data' => $tasks, 'completed_tasks' => $completed_tasks, 'notification' => true, 'new_messages_notifications' => $new_messages_notifications];
      } else {
        $message = ['result' => 'success', 'data' => $tasks, 'completed_tasks' => $completed_tasks, 'notification' => false,'new_messages_notifications' => $new_messages_notifications];
      }
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function sendMessageAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $messages = $this -> validation(13);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $messages = $this -> validation(11);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $task_id = $request -> getPost('taskId');
      $owner = CoursesUsers::findFirstByRoleId('3');
      $user_id = $this -> session -> get('user_id');
      $message = new Messages();
      $message -> text = $request -> getPost('taskMessage');
      $message -> date_time = date('Y-m-d H:i:s', time());
      $message -> task_id = $task_id;
      $message -> sender_id = $user_id;
      $message -> receptionist_id = $owner -> user_id;
      $message -> save();
      $receptionist_id = $owner -> user_id;
      $new_message_notification = NewMessagesNotifications::findFirst("task_id = $task_id and receptionist_id = $receptionist_id and sender_id = $user_id");
      if($new_message_notification == null) {
        $new_message_notification = new NewMessagesNotifications();
        $new_message_notification -> task_id = $task_id;
        $new_message_notification -> sender_id = $user_id;
        $new_message_notification -> receptionist_id = $owner -> user_id;
        $new_message_notification -> save();
      }
      $new_message_notification = NewMessagesNotifications::findFirst("task_id = $task_id and receptionist_id = $user_id");
      if($new_message_notification != null) {
        $new_message_notification -> delete();
      }
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function sendMessageAdminAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $messages = $this -> validation(13);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $messages = $this -> validation(11);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $task_id = $request -> getPost('taskId');
      $receptionist_id = $request -> getPost('receptionistId');
      $owner = CoursesUsers::findFirstByRoleId('3');
      $user_id = $this -> session -> get('user_id');
      $message = new Messages();
      $message -> text = $request -> getPost('taskMessage');
      $message -> date_time = date('Y-m-d H:i:s', time());
      $message -> task_id = $task_id;
      $message -> sender_id = $user_id;
      $message -> receptionist_id = $receptionist_id;
      $message -> save();
      $new_message_notification = NewMessagesNotifications::findFirst("task_id = $task_id and receptionist_id = $receptionist_id");
      if($new_message_notification == null) {
        $new_message_notification = new NewMessagesNotifications();
        $new_message_notification -> task_id = $task_id;
        $new_message_notification -> sender_id = $user_id;
        $new_message_notification -> receptionist_id = $receptionist_id;
        $new_message_notification -> save();
      }
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function getMessagesAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $messages = $this -> validation(11);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $task_id = $request -> get('taskId');
      $messages = Messages::find(['conditions' => "task_id = $task_id and (sender_id = $user_id or receptionist_id = $user_id)", 'order' => 'date_time DESC']);
      $courses = CoursesUsers::find();
      $completed_task = CompletedTasks::findFirst("task_id = $task_id and user_id = $user_id");
      $message = ['result' => 'success', 'data' => $messages, 'courses' => $courses, 'completed_task' => $completed_task];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function getAdminInfoAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $messages = $this -> validation(11);
      if (count($messages)) {
        foreach ($messages as $message) {
          array_push($result, $message);
        }
        return json_encode($result);
      }
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      if($course_user -> role_id == 1) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $owner_id = CoursesUsers::findFirst("course_id = $course_id and role_id = 3") -> user_id;
      $task_id = $request -> getPost('taskId');
      if(($request -> getPost('filter') == 'all') && ($request -> getPost('sort') == 'name')) {
        $course_users = CoursesUsers::find(['conditions' => "role_id = 1 and course_id = $course_id", 'order' => 'nickname ASC']);
        $new_messages_notifications = NewMessagesNotifications::find("task_id = $task_id and receptionist_id = $owner_id");
        $message = ['result' => 'success', 'data' => $course_users, 'new_messages_notifications' => $new_messages_notifications];
        array_push($result, $message);
        return json_encode($result);
      }
      if(($request -> getPost('filter') == 'all') && ($request -> getPost('sort') == 'message')) {
        $course_users = array();
        $messages = Messages::find([
          'conditions' => "task_id = $task_id",
          "order" => "date_time DESC"
        ]);
        foreach($messages as $message) {
          $sender_id = $message -> sender_id;
          $course_user = CoursesUsers::findFirst("course_id = $course_id and user_id = $sender_id");
          if($course_user -> role_id == 1) {
            $found = false;
            foreach($course_users as $user) {
              if($user -> user_id == $course_user -> user_id) {
                $found = true;
                break;
              }
            }
            if(!$found) {
              array_push($course_users, $course_user);
            }
          } else {
            $receptionist_id = $message -> receptionist_id;
            $course_user = CoursesUsers::findFirst("course_id = $course_id and user_id = $receptionist_id");
            $found = false;
            foreach($course_users as $user) {
              if($user -> user_id == $course_user -> user_id) {
                $found = true;
                break;
              }
            }
            if(!$found) {
              array_push($course_users, $course_user);
            }
          }
        }
        $users = CoursesUsers::findByCourseId($course_id);
        foreach ($users as $user) {
          $found = false;
          foreach ($course_users as $course_user) {
            if($course_user -> user_id == $user -> user_id) {
              $found = true;
              break;
            }
          }
          if(!$found && ($user -> role_id == 1)) {
            array_push($course_users, $user);
          }
        }
        $new_messages_notifications = NewMessagesNotifications::find("task_id = $task_id and receptionist_id = $owner_id");
        $message = ['result' => 'success', 'data' => $course_users, 'new_messages_notifications' => $new_messages_notifications];
        array_push($result, $message);
        return json_encode($result);
      }
      if(($request -> getPost('filter') == 'unread') && ($request -> getPost('sort') == 'name')) {
        $course_users = array();
        $user_id = $request -> getPost('userId');
        if($user_id != null) {
          $course_user = CoursesUsers::findFirst("course_id = $course_id and user_id = $user_id");
          $found = false;
          foreach($course_users as $user) {
            if($user -> user_id == $course_user -> user_id) {
              $found = true;
              break;
            }
          }
          if(!$found) {
            array_push($course_users, $course_user);
          }
        }
        $new_messages_notifications = NewMessagesNotifications::findByTaskId($task_id);
        foreach($new_messages_notifications as $new_message_notification) {
          $user_id = $new_message_notification -> sender_id;
          $course_user = CoursesUsers::findFirst("course_id = $course_id and user_id = $user_id");
          if($course_user -> role_id == 1) {
            $found = false;
            foreach($course_users as $user) {
              if($user -> user_id == $course_user -> user_id) {
                $found = true;
                break;
              }
            }
            if(!$found) {
              array_push($course_users, $course_user);
            }
          }
        }
      }
      if(($request -> getPost('filter') == 'unread') && ($request -> getPost('sort') == 'message')) {
        $course_users = array();
        $user_id = $request -> getPost('userId');
        if($user_id != null) {
          $course_user = CoursesUsers::findFirst("course_id = $course_id and user_id = $user_id");
          $found = false;
          foreach($course_users as $user) {
            if($user -> user_id == $course_user -> user_id) {
              $found = true;
              break;
            }
          }
          if(!$found) {
            array_push($course_users, $course_user);
          }
        }
        $messages = Messages::find([
          'conditions' => "task_id = $task_id",
          "order" => "date_time DESC"
        ]);
        $new_messages_notifications = NewMessagesNotifications::findByTaskId($task_id);
        foreach($messages as $message) {
          $sender_id = $message -> sender_id;
          foreach($new_messages_notifications as $new_message_notification) {
            if($new_message_notification -> sender_id == $sender_id) {
              $user = CoursesUsers::findFirst("course_id = $course_id and user_id = $sender_id");
              if($user -> role_id == 1) {
                $found = false;
                foreach($course_users as $course_user) {
                  if($course_user -> user_id == $user -> user_id) {
                    $found = true;
                    break;
                  }
                }
                if(!$found) {
                  array_push($course_users, $user);
                }
              }
            }
          }
        }
      }
      $new_messages_notifications = NewMessagesNotifications::find("task_id = $task_id and receptionist_id = $owner_id");
      $message = ['result' => 'success', 'data' => $course_users, 'new_messages_notifications' => $new_messages_notifications];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function getUserInfoAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      if($course_user -> role_id == 1) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $task_id = $request -> get('taskId');
      $user_id = $request -> get('userId');
      $messages = Messages::find(['conditions' => "task_id = $task_id and (sender_id = $user_id or receptionist_id = $user_id)", 'order' => 'date_time DESC']);
      $courses = CoursesUsers::find();
      $files = UsersFiles::find("user_id = $user_id and task_id = $task_id");
      $completed_task = CompletedTasks::findFirst("task_id = $task_id and user_id = $user_id");
      $message = ['result' => 'success', 'data' => $messages, 'courses' => $courses, 'files' => $files, 'completed_task' => $completed_task];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function scoreTaskAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $nickname = $course_user -> nickname;
      if($course_user -> role_id == 1) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $completed_task = new CompletedTasks();
      $completed_task -> task_id = $request -> get('taskId');
      $completed_task -> user_id = $request -> get('userId');
      $completed_task -> score = $request -> get('score');
      $completed_task -> save();
      $mes = new Messages();
      $mes -> text = "$nickname принял(а) задание";
      $mes-> date_time = date('Y-m-d H:i:s', time());
      $mes -> task_id = $request -> get('taskId');
      $mes -> sender_id = $user_id;
      $mes -> receptionist_id = $request -> get('userId');
      $mes -> save();
      $receptionist_id = $request -> get('userId');
      $task_id = $request -> get('taskId');
      $new_message_notification = NewMessagesNotifications::findFirst("task_id = $task_id and receptionist_id = $receptionist_id");
      if($new_message_notification == null) {
        $new_message_notification = new NewMessagesNotifications();
        $new_message_notification -> task_id = $task_id;
        $new_message_notification -> sender_id = $user_id;
        $new_message_notification -> receptionist_id = $receptionist_id;
        $new_message_notification -> save();
      }
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

  public function cancelScoreTaskAction() {
    $request = $this -> request;
    if(!$this -> checkUserPassword()) {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
    if ($request -> isPost() && $request -> isAjax()) {
      $result = $this -> getToken();
      $course_id = $request -> getPost('courseId');
      if(!$this -> checkUserCourse($course_id)) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет доступа к этому курсу!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $user_id = $this -> session -> get('user_id');
      $course_user = CoursesUsers::findFirst("user_id = $user_id and course_id = $course_id");
      $nickname = $course_user -> nickname;
      if($course_user -> role_id == 1) {
        $message = ['result' => 'error', 'message' => 'У данного пользователя нет прав!'];
        array_push($result, $message);
        return json_encode($result);
      }
      $student_id = $request -> get('userId');
      $task_id = $request -> get('taskId');
      $completed_task = CompletedTasks::findFirst("task_id = $task_id and user_id = $student_id");
      if($completed_task == null) {
        $message = ['result' => 'error', 'message' => 'Отсутствует задание'];
        array_push($result, $message);
        return json_encode($result);
      }
      $completed_task -> delete();
      // $completed_task = new CompletedTasks();
      // $completed_task -> task_id = $request -> get('taskId');
      // $completed_task -> user_id = $request -> get('userId');
      // $completed_task -> score = $request -> get('score');
      // $completed_task -> save();
      $mes = new Messages();
      $mes -> text = "$nickname отменил(а) приём задания";
      $mes-> date_time = date('Y-m-d H:i:s', time());
      $mes -> task_id = $request -> get('taskId');
      $mes -> sender_id = $user_id;
      $mes -> receptionist_id = $request -> get('userId');
      $mes -> save();
      $receptionist_id = $request -> get('userId');
      $task_id = $request -> get('taskId');
      $new_message_notification = NewMessagesNotifications::findFirst("task_id = $task_id and receptionist_id = $receptionist_id");
      if($new_message_notification == null) {
        $new_message_notification = new NewMessagesNotifications();
        $new_message_notification -> task_id = $task_id;
        $new_message_notification -> sender_id = $user_id;
        $new_message_notification -> receptionist_id = $receptionist_id;
        $new_message_notification -> save();
      }
      $message = ['result' => 'success'];
      array_push($result, $message);
      return json_encode($result);
    } else {
      $this -> response -> redirect ('/profile/courses');
      return;
    }
  }

}
