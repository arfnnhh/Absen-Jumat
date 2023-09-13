<?php
session_start();

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'db_absen_jumat';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$userId = $_SESSION['user_id'];

$userRayonQuery = "SELECT rayon FROM users WHERE id = '$userId'";
$userRayonResult = $conn->query($userRayonQuery);

$userLevelQuery = "SELECT level FROM users WHERE id = '$userId'";
$userLevelResult = $conn->query($userLevelQuery);

$userLevel = null;
$userRayon = null;

if ($userLevelResult->num_rows === 1) {
    $userRow = $userLevelResult->fetch_assoc();
    $userLevel = $userRow['level'];
}

if ($userRayonResult->num_rows === 1) {
    $userRow = $userRayonResult->fetch_assoc();
    $userRayon = $userRow['rayon'];

    if ($userRayon !== 'Kesis') {
        $sql = "SELECT * FROM siswa WHERE rayon = '$userRayon'";
    } else {
        $sql = "SELECT * FROM siswa";
    }

    $result = $conn->query($sql);
}

if ($userRayon !== 'Kesis') {
    $rekapSql = "SELECT * FROM rekap WHERE rayon = '$userRayon'";
} else {
    $rekapSql = "SELECT * FROM rekap";
}
$rekapResult = $conn->query($rekapSql);
$rekapResult1 = $conn->query($rekapSql);
$rekapResult2 = $conn->query($rekapSql);

if (isset($_POST['search_button'])) {
    $searchDate = $_POST['search_date'];
    $userRayon = $_SESSION['rayon'];

    $stmt = $conn->prepare("SELECT * FROM rekap WHERE tanggal = ? AND rayon = ?");

    if ($stmt) {
        $stmt->bind_param("ss", $searchDate, $userRayon);
        if ($stmt->execute()) {
            $rekapResult = $stmt->get_result();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-900">
<h1 class="mb-6 text-center text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Absen Shalat Jum'at</h1>
<hr class="h-px my-5 bg-gray-200 border-0 dark:bg-gray-700">
<a href="logout.php" class="float-right focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Logout</a>
<div class="inline-flex" role="group">
    <button data-modal-target="defaultModal" data-modal-toggle="defaultModal" class="mb-3 block mr-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
        Hadir
    </button>
    <button data-modal-target="defaultModal1" data-modal-toggle="defaultModal1" class="mb-3 block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" type="button">
        Tidak Hadir
    </button>
    <form action="" method="POST" class="ml-5 mb-3">
        <input type="date" name="search_date" id="search_date" class="rounded-md" required>
        <button type="submit" name="search_button" class="bg-blue-700 text-white rounded-md p-2">Cari</button>
    </form>
    <?php if ($userLevel == 2 || $userLevel == 3) {
        echo '<a href="dashboard.php" class="mb-3 focus:outline-none ml-7 text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Absen</a>';
    }?>
</div>

<div id="defaultModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Daftar siwa yang hadir
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="defaultModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <?php while ($row = $rekapResult1->fetch_assoc()) {
                    echo '<ul class="max-w-md space-y-1 text-gray-500 list-inside dark:text-gray-400">';
                    if ($row['status'] === 'Hadir') {
                        echo '<li class="flex items-center">
                    <svg class="w-3.5 h-3.5 mr-2 text-green-500 dark:text-green-400 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    ' . $row['nama'] . '
                </li>';
                    }
                    echo '</ul>';
                }?>
            </div>
        </div>
    </div>
</div>

<div id="defaultModal1" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Daftar siwa yang tidak hadir
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="defaultModal1">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <?php while ($row = $rekapResult2->fetch_assoc()) {
                    echo '<ul class="max-w-md space-y-1 text-gray-500 list-inside dark:text-gray-400">';
                    if ($row['status'] === 'Tidak Hadir') {
                        echo '<li class="flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-2 text-gray-500 dark:text-gray-400 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                    </svg>
                                    ' . $row['nama'] . '
                              </li>';
                    }
                    echo '</ul>';
                }?>
            </div>
        </div>
    </div>
</div>

<?php if ($userLevel == 3 || $userLevel == 2) { ?>
<table class="w-full text-s text-left text-gray-600 dark:text-gray-400 table auto">
    <thead class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
    <tr>
        <th scope="col" class="px-6 py-3">
            Name
        </th>
        <th scope="col" class="px-6 py-3">
            NIS
        </th>
        <th scope="col" class="px-6 py-3">
            Rombel
        </th>
        <th scope="col" class="px-6 py-3">
            Rayon
        </th>
        <th scope="col" class="px-6 py-3">
            Kehadiran
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    echo '<form action="updateFunc.php" method="POST">';
    while ($row = $result->fetch_assoc()) {
        $tanggalQuery = "SELECT tanggal FROM rekap WHERE nis = '" . $row['nis'] . "'";
        $tanggalResult = $conn->query($tanggalQuery);

        if ($tanggalResult->num_rows === 1) {
            $tanggalRow = $tanggalResult->fetch_assoc();
            $tanggal = $tanggalRow['tanggal'];
        } else {
            $tanggal = "";
        }

        echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
        echo '<td class="px-6 py-4"><input type="text" name="nama[' . $row['nis'] . ']" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="' . $row['nama'] . '"/></td>';
        echo '<td class="px-6 py-4"><input type="number" name="nis[' . $row['nis'] . ']" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="' . $row['nis'] . '"/></td>';
        echo '<td class="px-6 py-4"><input type="text" name="rombel[' . $row['nis'] . ']" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="' . $row['rombel'] . '"/></td>';
        echo '<td class="px-6 py-4"><input type="text" name="rayon[' . $row['nis'] . ']" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="' . $row['rayon'] . '"/></td>';
        echo '<input type="hidden" name="tanggal[' . $row['nis'] . ']" id="tanggal" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="' . $tanggal . '"/>';
        echo '<td class="px-6 py-4">';
        echo '<div class="flex items-center h-5">';

        $hadirChecked = ($row['status'] === 'hadir') ? 'checked' : '';
        $tidakHadirChecked = ($row['status'] === 'tidak-hadir') ? 'checked' : '';

        echo '<input type="radio" name="status[' . $row['nis'] . ']" value="hadir" class="w-4 mr-2 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required ' . $hadirChecked . '> Hadir';
        echo '<input type="radio" name="status[' . $row['nis'] . ']" value="tidak-hadir" class="w-4 ml-5 mr-2 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required ' . $tidakHadirChecked . '> Tidak Hadir';
        echo '</div>';
        echo '</td>';
        echo '</tr>';
    }
    ?>
    <input type="submit" class="text-white ml-5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-6 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" required value="Perbarui">
    </form>
    </tbody>
</table>
<?php } ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
</body>
</html>
