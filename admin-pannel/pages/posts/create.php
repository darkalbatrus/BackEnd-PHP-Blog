<?php
include "../../include/layout/header.php";
$categories = $db->query("SELECT * FROM categories");

$invalidInputTitle = '';
$invalidInputAuthor = '';
$invalidInputImage = '';
$invalidInputBody = '';
if (isset($_POST['addPost'])) {

    if (empty(trim($_POST['title']))) {
        $invalidInputTitle = 'فیلد عنوان الزامی است';
    }
    if (empty(trim($_POST['author']))) {
        $invalidInputAuthor = 'فیلد نام نویسنده الزامی است';
    }
    if (empty(trim($_FILES['image']['name']))) {
        $invalidInputImage = 'عکس مقاله الزامی است';
    }
    if (empty(trim($_POST['body']))) {
        $invalidInputBody = 'متن مقاله الزامی است';
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
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
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
                                    <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
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
                        <textarea
                            name="body"
                            class="form-control"
                            rows="6"></textarea>
                        <div class="form-text text-danger"><?= $invalidInputBody ?></div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-dark" name="addPost">
                            ایجاد
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