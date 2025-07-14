<?php
include "../../include/layout/header.php";

if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $post = $db->prepare("SELECT * FROM posts WHERE id = :id");
    $post->execute(['id' => $postId]);
    $post = $post->fetch();
    $categories = $db->query("SELECT * FROM categories");
    $image = $db->prepare("SELECT * FROM files WHERE id=:id");
    $image->execute(['id' => $post['file_id']]);
    $image = $image->fetch();
    // echo "<pre>";
    // print_r($image);
}

$invalidInputTitle = '';
$invalidInputAuthor = '';
$invalidInputBody = '';
$invalidInputImage = '';

if (isset($_POST['editPost'])) {
    if (empty(trim($_POST['title']))) {
        $invalidInputTitle = 'فیلد عنوان الزامی است';
    } elseif (mb_strlen(trim($_POST['title'])) < 3) {
        $invalidInputTitle = 'حداقل 3 کاراکتر باشد';
    } elseif (mb_strlen(trim($_POST['title'])) > 20) {
        $invalidInputTitle = 'حداکثر 20 کاراکتر باشد';
    }

    if (empty(trim($_POST['author']))) {
        $invalidInputAuthor = 'فیلد نام نویسنده الزامی است';
    } elseif (mb_strlen(trim($_POST['author'])) < 3) {
        $invalidInputAuthor = 'حداقل 3 کاراکتر باشد';
    } elseif (mb_strlen(trim($_POST['author'])) > 20) {
        $invalidInputAuthor = 'حداکثر 20 کاراکتر باشد';
    }

    if (empty(trim($_POST['body']))) {
        $invalidInputBody = 'متن مقاله الزامی است';
    } elseif (mb_strlen(trim($_POST['body'])) < 10) {
        $invalidInputBody = 'حداقل 10 کاراکتر باشد';
    } elseif (mb_strlen(trim($_POST['body'])) > 1000) {
        $invalidInputBody = 'حداکثر 1000 کاراکتر باشد';
    }

    if (!empty(trim($_POST['title'])) && !empty(trim($_POST['author'])) && !empty(trim($_POST['body']))) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $body = $_POST['body'];
        $categoryId = $_POST['categoryId'];
        $imageId = $image['id'];

        if (!empty(trim($_FILES['image']['name']))) {
            $allowType = ['image/png', 'image/jpg', 'image/jpeg'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);

            if (!in_array($fileType, $allowType)) {
                $invalidInputImage = 'فرمت عکس معتبر نیست';
            } else {
                $fileSize = $_FILES['image']['size'];

                if ($fileSize < 1 * 1024 * 1024) {
                    $invalidInputImage = "حداقل سایز عکس 1 مگابایت باشد";
                } elseif ($fileSize > 10 * 1024 * 1024) {
                    $invalidInputImage = "حداکثر سایز عکس 10 مگابایت باشد";
                } elseif (empty($invalidInputImage)) {
                    $imgName = time() . "-" . basename($_FILES['image']['name']);
                    $imgType = $_FILES['image']['type'];
                    $imgSize = $_FILES['image']['size'];
                    $tmpName = $_FILES['image']['tmp_name'];

                    if (move_uploaded_file($tmpName, "../../../uploads/posts/$imgName")) {
                        unlink("../../../uploads/posts/{$image['name']}");

                        $imgUpdate = $db->prepare("UPDATE files SET name=:name,size=:size,type=:type WHERE id=:id");
                        $imgUpdate->execute(['name' => $imgName, 'type' => $imgType, 'size' => $imgSize, 'id' => $imageId]);

                        $postUpdate = $db->prepare("UPDATE posts SET title =:title, author=:author, category_id=:categoryId, body=:body WHERE id=:id");
                        $postUpdate->execute(['title' => $title, 'author' => $author, 'categoryId' => $categoryId, 'body' => $body, 'id' => $postId]);
                    } else {
                        echo "Upload Error";
                    }
                }
            }
        } else {
            $postUpdate = $db->prepare("UPDATE posts SET title =:title, author=:author, category_id=:categoryId, body=:body WHERE id=:id");
            $postUpdate->execute(['title' => $title, 'author' => $author, 'categoryId' => $categoryId, 'body' => $body, 'id' => $postId]);
        }
        if (empty($invalidInputTitle) && empty($invalidInputAuthor) && empty($invalidInputImage) && empty($invalidInputBody)) {
            header("Location:index.php");
            exit();
        }
    }
}

?>


<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php
        include "../../include/layout/sidebar.php";
        ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ویرایش مقاله</h1>
            </div>

            <!-- Posts -->
            <div class="mt-4">
                <form class="row g-4" method="POST" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان مقاله</label>
                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="<?= $post['title'] ?>" />
                        <div class="form-text text-danger"><?= $invalidInputTitle ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">نویسنده مقاله</label>
                        <input
                            name="author"
                            type="text"
                            class="form-control"
                            value="<?= $post['author'] ?>" />
                        <div class="form-text text-danger"><?= $invalidInputAuthor ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">دسته بندی مقاله</label>
                        <select class="form-select" name="categoryId">
                            <?php if ($categories->rowCount() == 0): ?>
                                <option value="1" disabled>دسته بندی یافت نشد</option>
                            <?php else: ?>
                                <?php foreach ($categories as $category): ?>
                                    <option <?= ($category['id'] == $post['category_id']) ? 'selected' : '' ?> value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر مقاله</label>
                        <input class="form-control" name="image" type="file" />
                        <div class="form-text text-danger"><?= $invalidInputImage ?></div>
                    </div>

                    <div class="col-12">
                        <label for="formFile" class="form-label">متن مقاله</label>
                        <textarea class="form-control" name="body" rows="8">
                            <?= $post['body'] ?>
                        </textarea>
                        <div class="form-text text-danger"><?= $invalidInputBody ?></div>
                    </div>


                    <div class="col-12 col-sm-6 col-md-4">
                        <img class="rounded" src="../../../uploads/posts/<?= $image['name'] ?>" width="300" />
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-dark" name="editPost">
                            ویرایش
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php
include "../../include/layout/footer.php";
?>