<?php
include("config.php");
include("classes/SiteResultsProvider.php");
include("classes/ImageResultsProvider.php");
    if(isset($_GET["term"])){
        $term = $_GET["term"];
    }else{
        exit("please letter search > 0");
    }
    $type = isset($_GET["type"]) ? $_GET["type"] : "sites";
    $page = isset($_GET["page"]) ? $_GET["page"] : 1;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="headerContent">
                <!-- div class="logoContainer">
                    <a href="index.php">

                        <img src="assets/images/search.png" alt="search Title">
                    </a>
                </div> -->
                <div class="searchContainer">
                    <form action="search.php" method="get">
                        <div class="searchBarContainer">
                            <input type="hidden" name="type" value="<?php echo $type; ?>">
                            <input type="text" class="searchBox" name="term" value="<?php echo $term; ?>">
                            <!-- <button class="searchButton"><img src="assets/images/icons/search.png"></button> -->
                        </div>
                    </form>
                </div>
            </div>
            <div class="tabsContainer">
                <ul class="tabList">
                    <li class="<?php echo $type == 'sites' ? 'active' : '' ?>">
                        <a href="<?php echo "search.php?term=$term&type=sites"; ?>">
                            All
                        </a>
                    </li>
                    <li class="<?php echo $type == 'images' ? 'active' : '' ?>">
                        <a href="<?php echo "search.php?term=$term&type=images"; ?>">
                            Images
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mainResultsSection">
            <?php
                if($type == "sites"){
                    $resultsProvider = new SiteResultsProvider($con);
                    $pageLimit = 20;
                }else{
                    $resultsProvider = new ImageResultsProvider($con);
                    $pageLimit = 30;
                }

                $numResults = $resultsProvider->getNumResults($term);
                echo "<p class='resultsCount'>About $numResults results</p>";
                echo $resultsProvider->getResultsHtml($page, $pageLimit, $term);
            ?>
        </div>
        <div class="paginationContainer">
            <div class="pageButtons">
                <div class="pageNumberContainer">
                   
                </div>
                <?php
                 // maximun number of page displayed
                $pagesToShow = 10;

                 // number of posts per page
                $pageSize = 20;


                // the number of pages is equal to the rounding of the article divided by the number of posts per page
                // ceil -> used to round a number to the nearest integer 
                $numPages = ceil($numResults / $pageSize); 

                // Remaining pages is equal to the smallest number between phase to display and page number
                // min -> used to find the lowest value in the array or the lowest value of several specified values
                $pageLefts = min($pagesToShow, $numPages);

                // current page is equal to $ page get minus for rounding down (the page displayed is divided by 2)
                $currentPage = $page - floor( $pagesToShow / 2 ); 

                 // if the current page is less than 1, the current page set will be 1
                if($currentPage < 1){ 

                    $currentPage = 1;
                }

                // if the current page plus the remaining pages is less than the total number of pages plus 1
                if($currentPage + $pageLefts > $numPages + 1) { 

                     // then the current page number equals the total number of pages + 1 - the remaining pages 
                    $currentPage = $numPages + 1 - $pageLefts; 
                }

                // if the number of remaining pages is not 0 and the current page number is less than or equal to the total number of pages
                while($pageLefts != 0 && $currentPage <= $numPages) { 
                    if($currentPage == $page){
                        echo "<div class='pageNumberContainer'>
                            
                            <span class='pageNumber'>$currentPage</span>
                          </div>";
                    }else{
                        echo "<div class='pageNumberContainer'>
                                  <a href='search.php?term=$term&type=$type&page=$currentPage'>
                                  
                                    <span class='pageNumber'>$currentPage</span>
                                  </a>
                              </div>";
                    }
                    $currentPage++;
                    $pageLefts--;
                }

                ?>
                <div class="pageNumberContainer">
                    
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>