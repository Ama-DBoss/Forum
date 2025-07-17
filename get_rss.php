<?php
ini_set('max_execution_time', 300); // Allow up to 5 minutes for execution

// Database configuration
$host = 'localhost';
$dbname = 'forum';
$username = 'root';
$password = ''; // Update with your DB password

// Array of RSS sources
$rssSources = [
    'GENERAL' => [
        'http://feeds.abcnews.com/abcnews/usheadlines',
        'http://rss.cnn.com/rss/cnn_topstories.rss',
		'http://www.cbsnews.com/latest/rss/main',
		'http://www.nytimes.com/services/xml/rss/nyt/National.xml',
		'http://feeds.nbcnews.com/feeds/topstories',
        'https://www.aljazeera.com/xml/rss/all.xml',
    ],
    'POLITICS' => [
        'https://www.thenation.com/subject/politics/feed/',
        'http://rss.cnn.com/rss/cnn_allpolitics.rss',
        'https://feeds.bbci.co.uk/news/politics/rss.xml',
        'https://feeds.washingtonpost.com/rss/politics',
    ],
    'BUSINESS, FINANCE & STOCKS' => [
        'http://rss.nytimes.com/services/xml/rss/nyt/Business.xml',
        'http://www.forbes.com/health/feed2/',
    ],
    'TECH GADGETS' => [
        'https://rss.nytimes.com/services/xml/rss/nyt/Technology.xml',
        'http://feeds.feedburner.com/TechCrunch/',
    ],
    'SPORTS HIGHLIGHTS & BETTINGS' => [
        'http://sports.espn.go.com/espn/rss/news',
        'http://feeds.bbci.co.uk/sport/rss.xml',
        'https://www.skysports.com/rss',
        'https://supersport.com/rss/video',
        'https://www.sportsinsights.com/sports-insights-rss-feed/',
        'https://www.rotowire.com/rss/',
        'https://rss.app/en/rss-feed/sportsline-rss-feed',
        'https://supersport.com/rss/video',
    ],
    'HEALTH, DIET & EXERCISE' => [
        'http://rss.cnn.com/rss/cnn_health.rss',
        'http://www.nytimes.com/services/xml/rss/nyt/Health.xml',
    ],
    'CELEBRITY GOSSIPS' => [
        'https://www.buzzfeed.com/rss',
		'https://www.etonline.com/rss',
		'https://www.tmz.com/rss.xml',
    ],
    'LOVE, DATING & MARRIAGE' => [
        'https://unveiledwife.com/feed/',
        'https://awesomemarriage.com/blog?format=RSS',
        'https://www.gottman.com/blog/feed/',
        'https://themarriageandfamilyclinic.com/feed/',
    ],
    'CRYPTO & FOREX' => [
        'https://cointelegraph.com/rss',
        'https://bitcoinist.com/feed/',
        'https://traderlion.com/feed/',
		'https://crypto.news/feed/',
    ],
    'REAL ESTATE' => [
        'https://www.redfin.com/blog/feed/',
        'https://www.inman.com/feed',
    ],
    'ENTERPRENEURSHIP' => [
        'https://www.entrepreneur.com/latest.rss',
        'https://seths.blog/feed/',
        'https://feeds.feedblitz.com/WomenonBusiness',
        'https://feeds.feedburner.com/startupmindset',
    ],
    'ENTERTAINMENT' => [
        'https://www.buzzfeed.com/rss',
		'https://www.etonline.com/rss',
		'https://www.tmz.com/rss.xml',
    ],
    'AUTOMOBILES' => [
        'http://feeds.feedburner.com/Speedhunters',
        'https://www.caranddriver.com/rss/all.xml/',
    ],
    'BEAUTY, SKINCARE & FRAGRANCE' => [
        'https://www.katesomerville.com/blogs/news.atom',
        'https://eminenceorganics.com/us/blog/rss.xml',
    ],
    'EDUCATIONAL' => [
        'https://wenr.wes.org/feed',
        'https://theknowledgereview.com/feed/',
    ],
    'TRAVEL, TOURISM & VACATIONS' => [
        'https://www.nomadicmatt.com/feed/',
        'http://feeds.feedburner.com/goatsontheroad/AJHf',
    ],
    'WILDLIFE & NATIONAL GEOGRAPHIC' => [
        'https://blog.explore.org/feed/',
        'https://feeds.feedburner.com/ConservationInternationalBlog',
    ],
    'SCIENCE & FICTIONS' => [
        'https://clarkesworldmagazine.com/?feed=rss2',
        'https://www.cancelledscifi.com/feed/',
    ],
    'FASHION' => [
        'https://juliaberolzheimer.com/feed/',
        'https://fashionbombdaily.com/feed/',
    ],
    'RELIGION' => [
        'https://religionnews.com/feed/',
        'https://www.worldreligionnews.com/feed/',
        'https://therevealer.org/feed/',
        'https://religion.ua.edu/blog/feed/',
    ],
    'LIFESTYLE' => [
        'https://onbetterliving.com/feed/',
        'https://camillestyles.com/feed/',
        'https://lmgfl.com/feed/',
    ],
    'WORLD RECORDS' => [
        'https://www.worldrecordacademy.org/feed/rss2',
        'https://lmgfl.com/feed/',
    ],
];

// Function to fetch and parse RSS feeds
function fetchFeeds($sources, $category) {
    $feeds = [];
    foreach ($sources as $url) {
        $xml = @simplexml_load_file($url);
        if ($xml === false) {
            error_log("Failed to load RSS feed from: $url");
            continue;
        }

        foreach ($xml->channel->item as $item) {
            $image = '';

            // Attempt to fetch the image
            if (isset($item->enclosure['url'])) {
                $image = (string)$item->enclosure['url'];
            } elseif (preg_match('/<img[^>]+src="([^"]+)"/i', $item->description, $matches)) {
                $image = $matches[1];
            }

            $feeds[] = [
                'category' => $category,
                'title' => (string)$item->title,
                'link' => (string)$item->link,
                'copyright' => $item->copyright ?? '',
                'description' => strip_tags((string)$item->description),
                'pub_date' => date('Y-m-d H:i:s', strtotime((string)$item->pubDate)),
                'files' => $image,
            ];
        }
    }
    return $feeds;
}

// Function to store feeds in the database
function storeFeeds($feeds) {
    global $host, $dbname, $username, $password;

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the statement for inserting new feeds
        $stmt = $pdo->prepare("
            INSERT INTO rss_feeds (category_name, title, content, copyright, files, pub_date, link) 
            VALUES (:category, :title, :description, :copyright, :files, :pub_date, :link)
        ");

        // Insert the feeds into the database
        foreach ($feeds as $feed) {
            $stmt->execute([
                ':category' => $feed['category'],
                ':title' => $feed['title'],
                ':description' => $feed['description'],
                ':copyright' => $feed['copyright'],
                ':files' => $feed['files'],
                ':pub_date' => $feed['pub_date'],
                ':link' => $feed['link'],
            ]);
        }
        echo "Feeds stored successfully.\n";
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "Failed to store feeds. Check error logs.\n";
    }
}

// Main script execution
foreach ($rssSources as $category => $sources) {
    echo "Fetching feeds for category: $category...\n";
    $feeds = fetchFeeds($sources, $category);

    if (!empty($feeds)) {
        storeFeeds($feeds);
    } else {
        echo "No valid feeds found for category: $category.\n";
    }
}
?>
