<?php
include "./include/layout/header.php";
if (isset($_GET['post'])) {
    $postId = $_GET['post'];

    $post = $db->prepare("SELECT * FROM posts WHERE id = :id ");
    $post->execute(["id" => $postId]);
    $post = $post->fetch();
}
?>

<main>
    <!-- Content -->
    <section class="mt-4">
        <div class="row">
            <!-- Posts & Comments Content -->
            <?php if (empty($post)): ?>
                <div class="col-lg-8">
                    <div class="alert alert-danger">
                        مقاله ای یافت نشد ...
                    </div>
                </div>
            <?php else: ?>
                <div class="col-lg-8">
                    <div class="row justify-content-center">
                        <!-- Post Section -->
                        <div class="col">
                            <div class="card">
                                <img
                                    src="./uploads/posts/<?= $post['image'] ?>"
                                    class="card-img-top"
                                    alt="post-image" />
                                <div class="card-body">
                                    <div
                                        class="d-flex justify-content-between">
                                        <h5 class="card-title fw-bold">
                                            <?= $post['title'] ?>
                                        </h5>
                                        <?php
                                        $categoryId = $post['category_id'];
                                        $category = ($db->query("SELECT title FROM categories WHERE id = $categoryId")->fetch())['title'];
                                        ?>
                                        <div>
                                            <span
                                                class="badge text-bg-secondary"><?= $category ?></span>
                                        </div>
                                    </div>
                                    <p
                                        class="card-text text-secondary text-justify pt-3">
                                        <?= $post['body'] ?>
                                    </p>
                                    <div>
                                        <p class="fs-6 mt-5 mb-0">
                                            نویسنده : <?= $post['author'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-4" />
                        <!-- Comment Section -->
                        <div class="col">
                            <!-- Comment Form -->
                            <div class="card">
                                <div class="card-body">
                                    <p class="fw-bold fs-5">
                                        ارسال کامنت
                                    </p>

                                    <form>
                                        <div class="mb-3">
                                            <label class="form-label">نام</label>
                                            <input
                                                type="text"
                                                class="form-control" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">متن کامنت</label>
                                            <textarea
                                                class="form-control"
                                                rows="3"></textarea>
                                        </div>
                                        <button
                                            type="submit"
                                            class="btn btn-dark">
                                            ارسال
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <hr class="mt-4" />
                            <?php
                            $postId = $post['id'];
                            $comments =   $db->prepare("SELECT * From comments WHERE post_id = :id AND status ='1'");
                            $comments->execute(['id' => $postId]);
                            ?>

                            <!-- Comment Content -->
                            <p class="fw-bold fs-6">تعداد کامنت : <?= $comments->rowCount() ?></p>
                            <?php foreach ($comments as $comment): ?>
                                <div class="card bg-light-subtle mb-3">
                                    <div class="card-body">
                                        <div
                                            class="d-flex align-items-center">
                                            <img
                                                src="./assets/images/profile.png"
                                                width="45"
                                                height="45"
                                                alt="user-profle" />

                                            <h5
                                                class="card-title me-2 mb-0">
                                                <?= $comment['name'] ?>
                                            </h5>
                                        </div>

                                        <p class="card-text pt-3 pr-3">
                                            <?= $comment['comment'] ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <!-- Sidebar Section -->
            <div class="col-lg-4">
                <!-- Sesrch Section -->
                <div class="card">
                    <div class="card-body">
                        <p class="fw-bold fs-6">جستجو در وبلاگ</p>
                        <form action="search.html">
                            <div class="input-group mb-3">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="جستجو ..." />
                                <button
                                    class="btn btn-secondary"
                                    type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Categories Section -->
                <div class="card mt-4">
                    <div class="fw-bold fs-6 card-header">
                        دسته بندی ها
                    </div>
                    <ul class="list-group list-group-flush p-0">
                        <li class="list-group-item">
                            <a
                                class="link-body-emphasis text-decoration-none"
                                href="#">طبیعت</a>
                        </li>
                        <li class="list-group-item">
                            <a
                                class="link-body-emphasis text-decoration-none"
                                href="#">گردشگری</a>
                        </li>
                        <li class="list-group-item">
                            <a
                                class="link-body-emphasis text-decoration-none"
                                href="#">تکنولوژی</a>
                        </li>
                        <li class="list-group-item">
                            <a
                                class="link-body-emphasis text-decoration-none"
                                href="#">متفرقه</a>
                        </li>
                    </ul>
                </div>

                <!-- Subscribue Section -->
                <div class="card mt-4">
                    <div class="card-body">
                        <p class="fw-bold fs-6">عضویت در خبرنامه</p>

                        <form>
                            <div class="mb-3">
                                <label class="form-label">نام</label>
                                <input
                                    type="text"
                                    class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ایمیل</label>
                                <input
                                    type="email"
                                    class="form-control" />
                            </div>
                            <div class="d-grid gap-2">
                                <button
                                    type="submit"
                                    class="btn btn-secondary">
                                    ارسال
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- About Section -->
                <div class="card mt-4">
                    <div class="card-body">
                        <p class="fw-bold fs-6">درباره ما</p>
                        <p class="text-justify">
                            لورم ایپسوم متن ساختگی با تولید سادگی
                            نامفهوم از صنعت چاپ و با استفاده از
                            طراحان گرافیک است. چاپگرها و متون بلکه
                            روزنامه و مجله در ستون و سطرآنچنان که
                            لازم است و برای شرایط فعلی تکنولوژی مورد
                            نیاز و کاربردهای متنوع با هدف بهبود
                            ابزارهای کاربردی می باشد.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
include "./include/layout/footer.php";
?>