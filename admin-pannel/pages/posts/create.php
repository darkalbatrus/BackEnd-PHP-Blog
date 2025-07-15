<?php
include "../../include/layout/header.php";
$categories = $db->query("SELECT * FROM categories");

$invalidInputTitle = '';
$invalidInputAuthor = '';
$invalidInputImage = '';
$invalidInputBody = '';
// echo "<pre>";
// print_r($imageFile = $_FILES['image']);

if (isset($_POST['addPost'])) {
    if (empty(trim($_POST['title']))) {
        $invalidInputTitle = 'فیلد عنوان الزامی است';
    } elseif (mb_strlen(trim($_POST['title'])) < 3) {
        $invalidInputTitle = 'حداقل 3 کاراکتر باشد';
    } elseif (mb_strlen(trim($_POST['title'])) > 20) {
        $invalidInputTitle = 'حداکثر 20 کاراکتر باشد';
    } elseif (!preg_match('/^[\p{L}0-9\s]+$/u', $_POST['title'])) {
        $invalidInputTitle = 'فقط حروف و اعداد مجاز هستند';
    }

    if (empty(trim($_POST['author']))) {
        $invalidInputAuthor = 'فیلد نام نویسنده الزامی است';
    } elseif (mb_strlen(trim($_POST['author'])) < 3) {
        $invalidInputAuthor = 'حداقل 3 کاراکتر باشد';
    } elseif (mb_strlen(trim($_POST['author'])) > 20) {
        $invalidInputAuthor = 'حداکثر 20 کاراکتر باشد';
    } elseif (!preg_match('/^[\p{L}0-9\s]+$/u', $_POST['author'])) {
        $invalidInputAuthor = 'فقط حروف و اعداد مجاز هستند';
    }


    if (empty(trim($_FILES['image']['name']))) {
        $invalidInputImage = 'عکس مقاله الزامی است';
    } else {
        $allowType = ['image/png', 'image/jpg', 'image/jpeg'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($fileType, $allowType)) {
            $invalidInputImage = 'فرمت عکس معتبر نیست';
        } else {
            $fileSize = $_FILES['image']['size'];

            if ($fileSize < 1 * 1024 * 1024) {
                $invalidInputImage = "حداقل سایز عکس 3 مگابایت باشد";
            } elseif ($fileSize > 10 * 1024 * 1024) {
                $invalidInputImage = "حداکثر سایز عکس 10 مگابایت باشد";
            }
        }
    }


    if (empty(trim($_POST['body']))) {
        $invalidInputBody = 'متن مقاله الزامی است';
    } elseif (mb_strlen(trim($_POST['body'])) < 10) {
        $invalidInputBody = 'حداقل 10 کاراکتر باشد';
    } elseif (mb_strlen(trim($_POST['body'])) > 1000) {
        $invalidInputBody = 'حداکثر 1000 کاراکتر باشد';
    } elseif (!preg_match('/^[\p{L}0-9\s]+$/u', $_POST['body'])) {
        $invalidInputBody = 'فقط حروف و اعداد مجاز هستند';
    }


    if (empty($invalidInputTitle) && empty($invalidInputAuthor) && empty($invalidInputImage) && empty($invalidInputBody)) {
        $title = htmlspecialchars($_POST['title']);
        $author = htmlspecialchars($_POST['author']);
        // $nameImg = time() . "-" . basename($_FILES['image']['name']);
        $imgName = time() . "-" . basename($_FILES['image']['name']);
        $imgType = $_FILES['image']['type'];
        $imgSize = $_FILES['image']['size'];
        $categoryId = $_POST['categoryId'];
        $body = htmlspecialchars($_POST['body']);

        $tmpName = $_FILES['image']['tmp_name'];
        if (move_uploaded_file($tmpName, "../../../uploads/posts/$imgName")) {
            $imgSend = $db->prepare("INSERT INTO files (name,size,type) VALUES (:name, :size , :type)");
            $imgSend->execute(['name' => $imgName, 'type' => $imgType, 'size' => $imgSize]);
            $imgId = $db->query("SELECT id FROM files WHERE name = '$imgName'")->fetch();
            $imgId = $imgId['id'];
            $postSend = $db->prepare("INSERT INTO posts (title, body, category_id, author, file_id) VALUES (:title, :body, :category_id, :author, :file_id)");
            $postSend->execute(['title' => $title, 'body' => $body, 'category_id' => $categoryId, 'author' => $author, 'file_id' => $imgId]);

            header("Location: index.php");
            exit();
        } else {
            echo "خطا در بارگذاری تصویر ...";
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php include "../../include/layout/sidebar.php"; ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ایجاد مقاله</h1>
            </div>

            <!-- Posts -->
            <div class="mt-4">
                <form class="row g-4" enctype="multipart/form-data" method="post">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان مقاله</label>
                        <input type="text" name="title" class="form-control" />
                        <div class="form-text text-danger"><?= $invalidInputTitle ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">نویسنده مقاله</label>
                        <input type="text" name="author" class="form-control" />
                        <div class="form-text text-danger"><?= $invalidInputAuthor ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">دسته بندی مقاله</label>
                        <select class="form-select" name="categoryId">
                            <?php if ($categories->rowCount() == 0): ?>
                                <option value="1" disabled>دسته بندی یافت نشد</option>
                            <?php else: ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['title']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر مقاله</label>
                        <input class="form-control" name="image" type="file" />
                        <div class="form-text text-danger"><?= $invalidInputImage ?></div>
                    </div>

                    <div class="col-12">
                        <label for="formFile" class="form-label">متن مقاله</label>
                        <textarea name="body" class="form-control" rows="6"></textarea>
                        <div class="form-text text-danger"><?= $invalidInputBody ?></div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-dark" name="addPost">ایجاد</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include "../../include/layout/footer.php"; ?>