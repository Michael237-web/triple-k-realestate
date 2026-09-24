<?php
require_once __DIR__ . '/db.php';

/**
 * Simple spell checker and correction for common words
 */
function correctSpelling($text) {
    $commonWords = [
        // Greetings
        'morning' => 'morning',
        'mornig' => 'morning',
        'mornin' => 'morning',
        'mroning' => 'morning',
        'afternoon' => 'afternoon',
        'afternon' => 'afternoon',
        'afternun' => 'afternoon',
        'evening' => 'evening',
        'evenin' => 'evening',
        'evining' => 'evening',
        'evning' => 'evening',
        'hello' => 'hello',
        'helo' => 'hello',
        'hllo' => 'hello',
        'hi' => 'hi',
        'hey' => 'hey',
        'heyy' => 'hey',
        'howdy' => 'howdy',
        
        // Property types
        'house' => 'house',
        'hous' => 'house',
        'hse' => 'house',
        'apartment' => 'apartment',
        'apartement' => 'apartment',
        'appartment' => 'apartment',
        'aprtment' => 'apartment',
        'land' => 'land',
        'lnd' => 'land',
        'villa' => 'villa',
        'vila' => 'villa',
        'commercial' => 'commercial',
        'comercial' => 'commercial',
        'commrcial' => 'commercial',
        
        // Locations
        'nairobi' => 'nairobi',
        'nariobi' => 'nairobi',
        'nrobii' => 'nairobi',
        'mombasa' => 'mombasa',
        'mombassa' => 'mombasa',
        'mombsa' => 'mombasa',
        'kisumu' => 'kisumu',
        'nakuru' => 'nakuru',
        'eldoret' => 'eldoret',
        'thika' => 'thika',
        'machakos' => 'machakos',
        'kiambu' => 'kiambu',
        'ruiru' => 'ruiru',
        
        // Price related
        'price' => 'price',
        'pric' => 'price',
        'prce' => 'price',
        'cost' => 'cost',
        'cst' => 'cost',
        'budget' => 'budget',
        'budgt' => 'budget',
        'ksh' => 'ksh',
        'usd' => 'usd',
        
        // Help/Contact
        'help' => 'help',
        'hlp' => 'help',
        'contact' => 'contact',
        'contct' => 'contact',
        'contac' => 'contact',
        'phone' => 'phone',
        'phne' => 'phone',
        'email' => 'email',
        'emil' => 'email',
        'mail' => 'mail',
        
        // Process
        'buy' => 'buy',
        'byu' => 'buy',
        'sell' => 'sell',
        'sel' => 'sell',
        'rent' => 'rent',
        'rnt' => 'rent',
        'lease' => 'lease',
        'lase' => 'lease',
        
        // Thank you/Goodbye
        'thank' => 'thank',
        'thnk' => 'thank',
        'thanks' => 'thanks',
        'thnaks' => 'thanks',
        'goodbye' => 'goodbye',
        'goodby' => 'goodbye',
        'gdbye' => 'goodbye',
        'bye' => 'bye',
        'byee' => 'bye',
        'farewell' => 'farewell',
        'later' => 'later',
        'cya' => 'cya',
    ];
    
    // Split text into words
    $words = explode(' ', $text);
    $correctedWords = [];
    
    foreach ($words as $word) {
        $lowerWord = strtolower($word);
        // Check if word is in common words list
        if (isset($commonWords[$lowerWord])) {
            $correctedWords[] = $commonWords[$lowerWord];
        } else {
            // Check for similar words using levenshtein distance
            $bestMatch = null;
            $bestDistance = 3;
            
            foreach ($commonWords as $key => $value) {
                $distance = levenshtein($lowerWord, $key);
                if ($distance < $bestDistance) {
                    $bestDistance = $distance;
                    $bestMatch = $value;
                }
            }
            
            if ($bestMatch !== null && $bestDistance <= 2) {
                $correctedWords[] = $bestMatch;
            } else {
                $correctedWords[] = $word;
            }
        }
    }
    
    return implode(' ', $correctedWords);
}

/**
 * Parse price abbreviations like 100K, 1M, 1.5M, etc.
 * Returns the numeric value
 */
function parsePriceAbbreviation($text) {
    // Pattern to match numbers with K or M (case insensitive)
    // Examples: 100K, 1M, 1.5M, 2.5M, 500K, 10M
    $pattern = '/(\d+\.?\d*)\s*([km])/i';
    
    // Find all matches
    preg_match_all($pattern, $text, $matches);
    
    if (empty($matches[0])) {
        return null; // No price abbreviation found
    }
    
    $results = [];
    foreach ($matches[0] as $index => $match) {
        $number = floatval($matches[1][$index]);
        $unit = strtolower($matches[2][$index]);
        
        if ($unit === 'k') {
            $value = $number * 1000; // 100K = 100,000
        } elseif ($unit === 'm') {
            $value = $number * 1000000; // 1M = 1,000,000
        } else {
            $value = $number;
        }
        
        $results[] = [
            'original' => $match,
            'number' => $number,
            'unit' => $unit,
            'value' => $value,
            'formatted' => number_format($value)
        ];
    }
    
    return $results;
}

/**
 * Extract price range from message
 * Returns array with min and max values
 */
function extractPriceRange($message) {
    $minPrice = null;
    $maxPrice = null;
    
    // Check for patterns like "under 1M", "below 500K", "less than 2M"
    if (preg_match('/(under|below|less than|less\sthan|<)\s*(\d+\.?\d*)\s*([km]?)/i', $message, $matches)) {
        $number = floatval($matches[2]);
        $unit = strtolower($matches[3] ?? '');
        if ($unit === 'k') {
            $maxPrice = $number * 1000;
        } elseif ($unit === 'm') {
            $maxPrice = $number * 1000000;
        } else {
            $maxPrice = $number;
        }
        return ['min' => null, 'max' => $maxPrice];
    }
    
    // Check for patterns like "over 1M", "above 500K", "more than 2M"
    if (preg_match('/(over|above|more than|more\sthan|>)\s*(\d+\.?\d*)\s*([km]?)/i', $message, $matches)) {
        $number = floatval($matches[2]);
        $unit = strtolower($matches[3] ?? '');
        if ($unit === 'k') {
            $minPrice = $number * 1000;
        } elseif ($unit === 'm') {
            $minPrice = $number * 1000000;
        } else {
            $minPrice = $number;
        }
        return ['min' => $minPrice, 'max' => null];
    }
    
    // Check for patterns like "between 500K and 2M" or "500K - 2M"
    if (preg_match('/(between)\s*(\d+\.?\d*)\s*([km]?)\s*(and|to|-)\s*(\d+\.?\d*)\s*([km]?)/i', $message, $matches) ||
        preg_match('/(\d+\.?\d*)\s*([km]?)\s*(to|-)\s*(\d+\.?\d*)\s*([km]?)/i', $message, $matches)) {
        
        // Try to parse both numbers
        $num1 = floatval($matches[2] ?? $matches[1]);
        $unit1 = strtolower($matches[3] ?? $matches[2] ?? '');
        $num2 = floatval($matches[5] ?? $matches[4]);
        $unit2 = strtolower($matches[6] ?? $matches[5] ?? '');
        
        $val1 = $unit1 === 'k' ? $num1 * 1000 : ($unit1 === 'm' ? $num1 * 1000000 : $num1);
        $val2 = $unit2 === 'k' ? $num2 * 1000 : ($unit2 === 'm' ? $num2 * 1000000 : $num2);
        
        $minPrice = min($val1, $val2);
        $maxPrice = max($val1, $val2);
        
        return ['min' => $minPrice, 'max' => $maxPrice];
    }
    
    // Check for simple price with K or M (like "500K" or "2M")
    $parsed = parsePriceAbbreviation($message);
    if ($parsed && count($parsed) === 1) {
        $value = $parsed[0]['value'];
        // Check if it's a budget mention
        if (strpos($message, 'budget') !== false || strpos($message, 'price') !== false) {
            return ['min' => null, 'max' => $value];
        }
        return ['min' => $value, 'max' => $value];
    }
    
    return ['min' => null, 'max' => null];
}

/**
 * Format price for display with K/M abbreviations
 */
function formatPriceAbbreviated($amount) {
    if ($amount >= 1000000) {
        return number_format($amount / 1000000, 1) . 'M';
    } elseif ($amount >= 1000) {
        return number_format($amount / 1000, 0) . 'K';
    }
    return number_format($amount);
}

/**
 * Get time-based greeting
 */
function getTimeGreeting() {
    $hour = date('G');
    if ($hour >= 5 && $hour < 12) {
        return 'Good morning';
    } elseif ($hour >= 12 && $hour < 17) {
        return 'Good afternoon';
    } elseif ($hour >= 17 && $hour < 21) {
        return 'Good evening';
    } else {
        return 'Hello';
    }
}

/**
 * Get time-based closing
 */
function getTimeClosing() {
    $hour = date('G');
    if ($hour >= 5 && $hour < 12) {
        return 'Have a wonderful morning!';
    } elseif ($hour >= 12 && $hour < 17) {
        return 'Have a great afternoon!';
    } elseif ($hour >= 17 && $hour < 21) {
        return 'Have a lovely evening!';
    } else {
        return 'Have a good night!';
    }
}

/**
 * Enhanced chatbot response with spell checking and fuzzy matching
 */
function getChatbotResponse($message) {
    try {
        $db = Database::getInstance();
        
        // Step 1: Correct spelling
        $correctedMessage = correctSpelling($message);
        $originalMessage = $message;
        
        // If spelling was corrected, note it for the user
        $spellingNote = '';
        if ($correctedMessage !== $originalMessage) {
            $originalWords = explode(' ', strtolower($originalMessage));
            $correctedWords = explode(' ', strtolower($correctedMessage));
            $differences = array_diff($correctedWords, $originalWords);
            
            if (!empty($differences) && count($differences) <= 3) {
                $spellingNote = "📝 I understood you as: \"" . ucfirst($correctedMessage) . "\"\n\n";
            }
        }
        
        $message = strtolower(trim($correctedMessage));
        
        // FIRST: Check for time-based greetings (priority)
        if (strpos($message, 'good morning') !== false || strpos($message, 'morning') !== false) {
            $hour = date('G');
            if ($hour >= 5 && $hour < 12) {
                return $spellingNote . "🌅 Good morning! Welcome to Triple K Properties. How can I help you find your perfect property today?";
            } else {
                return $spellingNote . "🌅 Good morning! Welcome to Triple K Properties. How can I assist you today?";
            }
        }
        
        if (strpos($message, 'good afternoon') !== false || strpos($message, 'afternoon') !== false) {
            $hour = date('G');
            if ($hour >= 12 && $hour < 17) {
                return $spellingNote . "☀️ Good afternoon! Welcome to Triple K Properties. We're here to help you find your dream home.";
            } else {
                return $spellingNote . "☀️ Good afternoon! Welcome to Triple K Properties. What can I help you with?";
            }
        }
        
        if (strpos($message, 'good evening') !== false || strpos($message, 'evening') !== false) {
            $hour = date('G');
            if ($hour >= 17 && $hour < 21) {
                return $spellingNote . "🌙 Good evening! Triple K Properties is still here to assist you with your property needs.";
            } else {
                return $spellingNote . "🌙 Good evening! Welcome to Triple K Properties. How may I assist you?";
            }
        }
        
        // Check for simple greetings
        $simpleGreetings = ['hi', 'hello', 'hey', 'howdy', 'sup', 'yo'];
        $messageWords = explode(' ', $message);
        
        foreach ($messageWords as $word) {
            if (in_array($word, $simpleGreetings) || strlen($word) <= 3) {
                if (count($messageWords) <= 2) {
                    $greetingMsg = getTimeGreeting();
                    $responses = [
                        "👋 {$greetingMsg}! Welcome to Triple K Properties. How can I assist you today?",
                        "🏠 {$greetingMsg}! I'm your Triple K Properties assistant. Looking for a property?",
                        "✨ {$greetingMsg}! Welcome! I'm here to help you find your dream property.",
                        "🌟 {$greetingMsg}! Ready to explore our premium properties across Kenya?"
                    ];
                    return $spellingNote . $responses[array_rand($responses)];
                }
                break;
            }
        }
        
        // Check for "thank you"
        $thankYouWords = ['thank', 'thanks', 'thnaks', 'thnk', 'appreciate'];
        foreach ($thankYouWords as $word) {
            if (strpos($message, $word) !== false) {
                $responses = [
                    "😊 You're most welcome! Is there anything else I can help you with? Feel free to ask any questions about our properties or services.",
                    "🙏 Thank you for your kind words! If you need any more information about our properties, just let me know.",
                    "💫 You're welcome! I'm always here to help. What else would you like to know about Triple K Properties?"
                ];
                return $spellingNote . $responses[array_rand($responses)];
            }
        }
        
        // Check for goodbye
        $goodbyeWords = ['goodbye', 'goodby', 'bye', 'byee', 'farewell', 'later', 'cya', 'see you'];
        foreach ($goodbyeWords as $word) {
            if (strpos($message, $word) !== false) {
                $closing = getTimeClosing();
                $responses = [
                    "👋 Thank you for visiting Triple K Properties! {$closing} Feel free to come back anytime.",
                    "🏠 We look forward to helping you find your dream property. {$closing} Visit us again at Triple K Properties!",
                    "🌟 It was a pleasure chatting with you! {$closing} Don't hesitate to reach out if you have any questions."
                ];
                return $spellingNote . $responses[array_rand($responses)];
            }
        }
        
        // ===== PRICE ABBREVIATION HANDLING =====
        // Check for price abbreviations like 100K, 1M, etc.
        $parsedPrices = parsePriceAbbreviation($message);
        $priceRange = extractPriceRange($message);
        
        // If user mentions a price with K or M
        if (!empty($parsedPrices) || ($priceRange['min'] !== null || $priceRange['max'] !== null)) {
            $searchTerm = '';
            $priceKeyword = '';
            
            // Determine what they're looking for
            $propertyTypes = ['land', 'house', 'apartment', 'villa', 'commercial'];
            foreach ($propertyTypes as $type) {
                if (strpos($message, $type) !== false) {
                    $priceKeyword = $type;
                    $searchTerm = $type;
                    break;
                }
            }
            
            // If no property type specified, use a generic search
            if (empty($searchTerm)) {
                $searchTerm = 'property';
            }
            
            // Build the response
            $response = $spellingNote . "💰 I understand you're looking for a " . ucfirst($searchTerm) . " with a specific budget.\n\n";
            
            // Add price details
            if ($priceRange['min'] !== null && $priceRange['max'] !== null) {
                $response .= "📊 Price range: Ksh " . number_format($priceRange['min']) . " to Ksh " . number_format($priceRange['max']) . "\n\n";
                $response .= "Let me check what we have available in this range...\n\n";
                
                // Search for properties in this price range
                try {
                    $db = Database::getInstance();
                    $stmt = $db->query(
                        "SELECT * FROM t_properties WHERE status = 'available' 
                         AND price_ksh BETWEEN ? AND ? 
                         AND (property_type LIKE ? OR title LIKE ? OR location LIKE ?)
                         LIMIT 5",
                        [
                            $priceRange['min'], 
                            $priceRange['max'],
                            "%$searchTerm%", 
                            "%$searchTerm%", 
                            "%$searchTerm%"
                        ]
                    );
                    $properties = $stmt->fetchAll();
                    
                    if (!empty($properties)) {
                        $response .= "🔍 I found these properties within your budget:\n\n";
                        foreach ($properties as $prop) {
                            $response .= "🏠 {$prop['title']}\n";
                            $response .= "📍 {$prop['location']}\n";
                            $response .= "💰 Ksh " . number_format($prop['price_ksh']) . " (" . formatPriceAbbreviated($prop['price_ksh']) . ")\n";
                            if (!empty($prop['size_sqft']) && $prop['size_sqft'] > 0) {
                                $response .= "📐 " . number_format($prop['size_sqft']) . " sqft\n";
                            }
                            if (!empty($prop['bedrooms']) && $prop['bedrooms'] > 0) {
                                $response .= "🛏️ " . $prop['bedrooms'] . " bedrooms\n";
                            }
                            $response .= "🔗 View details: property-details.php?id=" . $prop['id'] . "\n\n";
                        }
                        $response .= "Would you like more details about any of these?";
                    } else {
                        $response .= "🔍 I couldn't find any {$searchTerm} properties in that price range right now. Would you like me to:\n";
                        $response .= "• Check a different price range?\n";
                        $response .= "• Look for other property types?\n";
                        $response .= "• Notify you when properties become available?";
                    }
                } catch (Exception $e) {
                    $response .= "I'm having trouble searching for properties right now. Please try again later.";
                }
                
                return $response;
                
            } elseif ($priceRange['max'] !== null) {
                // Under/below/less than X
                $response .= "📉 Maximum budget: Ksh " . number_format($priceRange['max']) . "\n\n";
                $response .= "Let me find properties under " . formatPriceAbbreviated($priceRange['max']) . "...\n\n";
                
                try {
                    $db = Database::getInstance();
                    $stmt = $db->query(
                        "SELECT * FROM t_properties WHERE status = 'available' 
                         AND price_ksh <= ? 
                         AND (property_type LIKE ? OR title LIKE ? OR location LIKE ?)
                         ORDER BY price_ksh DESC
                         LIMIT 5",
                        [$priceRange['max'], "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"]
                    );
                    $properties = $stmt->fetchAll();
                    
                    if (!empty($properties)) {
                        $response .= "🔍 I found these properties under " . formatPriceAbbreviated($priceRange['max']) . ":\n\n";
                        foreach ($properties as $prop) {
                            $response .= "🏠 {$prop['title']}\n";
                            $response .= "📍 {$prop['location']}\n";
                            $response .= "💰 Ksh " . number_format($prop['price_ksh']) . " (" . formatPriceAbbreviated($prop['price_ksh']) . ")\n";
                            if (!empty($prop['size_sqft']) && $prop['size_sqft'] > 0) {
                                $response .= "📐 " . number_format($prop['size_sqft']) . " sqft\n";
                            }
                            $response .= "🔗 View details: property-details.php?id=" . $prop['id'] . "\n\n";
                        }
                        $response .= "Would you like more details about any of these?";
                    } else {
                        $response .= "🔍 I couldn't find any {$searchTerm} properties under " . formatPriceAbbreviated($priceRange['max']) . " right now.\n\n";
                        $response .= "Would you like me to:\n";
                        $response .= "• Increase your budget?\n";
                        $response .= "• Look for other property types?\n";
                        $response .= "• Check other locations?";
                    }
                } catch (Exception $e) {
                    $response .= "I'm having trouble searching for properties right now. Please try again later.";
                }
                
                return $response;
                
            } elseif ($priceRange['min'] !== null) {
                // Over/above/more than X
                $response .= "📈 Minimum budget: Ksh " . number_format($priceRange['min']) . "\n\n";
                $response .= "Let me find properties above " . formatPriceAbbreviated($priceRange['min']) . "...\n\n";
                
                try {
                    $db = Database::getInstance();
                    $stmt = $db->query(
                        "SELECT * FROM t_properties WHERE status = 'available' 
                         AND price_ksh >= ? 
                         AND (property_type LIKE ? OR title LIKE ? OR location LIKE ?)
                         ORDER BY price_ksh ASC
                         LIMIT 5",
                        [$priceRange['min'], "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"]
                    );
                    $properties = $stmt->fetchAll();
                    
                    if (!empty($properties)) {
                        $response .= "🔍 I found these properties above " . formatPriceAbbreviated($priceRange['min']) . ":\n\n";
                        foreach ($properties as $prop) {
                            $response .= "🏠 {$prop['title']}\n";
                            $response .= "📍 {$prop['location']}\n";
                            $response .= "💰 Ksh " . number_format($prop['price_ksh']) . " (" . formatPriceAbbreviated($prop['price_ksh']) . ")\n";
                            if (!empty($prop['size_sqft']) && $prop['size_sqft'] > 0) {
                                $response .= "📐 " . number_format($prop['size_sqft']) . " sqft\n";
                            }
                            $response .= "🔗 View details: property-details.php?id=" . $prop['id'] . "\n\n";
                        }
                        $response .= "Would you like more details about any of these?";
                    } else {
                        $response .= "🔍 I couldn't find any {$searchTerm} properties above " . formatPriceAbbreviated($priceRange['min']) . " right now.\n\n";
                        $response .= "Would you like me to:\n";
                        $response .= "• Lower your budget?\n";
                        $response .= "• Look for other property types?\n";
                        $response .= "• Check other locations?";
                    }
                } catch (Exception $e) {
                    $response .= "I'm having trouble searching for properties right now. Please try again later.";
                }
                
                return $response;
            }
        }
        
        // Check for property search
        $propertyKeywords = ['land', 'house', 'apartment', 'villa', 'commercial', 'property', 'home', 'townhouse', 'condo'];
        $foundKeyword = null;
        foreach ($propertyKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                $foundKeyword = $keyword;
                break;
            }
        }
        
        if ($foundKeyword) {
            // Check for locations
            $locations = ['nairobi', 'mombasa', 'kisumu', 'nakuru', 'eldoret', 'thika', 'machakos', 'kiambu', 'ruiru'];
            $foundLocation = null;
            foreach ($locations as $loc) {
                if (strpos($message, $loc) !== false) {
                    $foundLocation = $loc;
                    break;
                }
            }
            
            $searchTerm = "%$foundKeyword%";
            if ($foundLocation) {
                $searchTerm = "%$foundLocation%";
            }
            
            $stmt = $db->query(
                "SELECT * FROM t_properties WHERE status = 'available' AND (property_type LIKE ? OR title LIKE ? OR location LIKE ?) LIMIT 5", 
                [$searchTerm, $searchTerm, $searchTerm]
            );
            $properties = $stmt->fetchAll();
            
            if (!empty($properties)) {
                $response = $spellingNote . "🔍 I found these properties matching your search:\n\n";
                foreach ($properties as $prop) {
                    $response .= "🏠 {$prop['title']}\n";
                    $response .= "📍 {$prop['location']}\n";
                    $response .= "💰 Ksh " . number_format($prop['price_ksh']) . " (" . formatPriceAbbreviated($prop['price_ksh']) . ")\n";
                    if (!empty($prop['size_sqft']) && $prop['size_sqft'] > 0) {
                        $response .= "📐 " . number_format($prop['size_sqft']) . " sqft\n";
                    }
                    if (!empty($prop['bedrooms']) && $prop['bedrooms'] > 0) {
                        $response .= "🛏️ " . $prop['bedrooms'] . " bedrooms\n";
                    }
                    $response .= "🔗 View details: property-details.php?id=" . $prop['id'] . "\n\n";
                }
                $response .= "Would you like more details about any of these properties?";
                return $response;
            }
        }
        
        // Check for price queries
        $priceKeywords = ['price', 'pric', 'prce', 'cost', 'cst', 'budget', 'budgt', 'how much'];
        foreach ($priceKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                // First check if there's a price abbreviation
                $parsed = parsePriceAbbreviation($message);
                
                if (!empty($parsed)) {
                    $priceValue = $parsed[0]['value'];
                    $formattedPrice = formatPriceAbbreviated($priceValue);
                    
                    if (strpos($message, 'land') !== false) {
                        return $spellingNote . "🌾 For Ksh " . number_format($priceValue) . " (" . $formattedPrice . "), you can find:\n\n" .
                               "• Land in Nairobi: Small plots or land in developing areas\n" .
                               "• Land in Mombasa: Medium plots in coastal areas\n" .
                               "• Land in Kisumu: Larger plots in lakeside areas\n" .
                               "• Land in rural areas: Several acres\n\n" .
                               "Tell me which location you're interested in!";
                    } elseif (strpos($message, 'apartment') !== false) {
                        return $spellingNote . "🏢 With a budget of Ksh " . number_format($priceValue) . " (" . $formattedPrice . "), you can get:\n\n" .
                               "• 1-bedroom apartments in Nairobi\n" .
                               "• 2-bedroom apartments in developing areas\n" .
                               "• Studio apartments in prime locations\n\n" .
                               "Would you like to see available options?";
                    } elseif (strpos($message, 'house') !== false || strpos($message, 'villa') !== false) {
                        return $spellingNote . "🏠 With a budget of Ksh " . number_format($priceValue) . " (" . $formattedPrice . "), you can get:\n\n" .
                               "• A modest 2-bedroom house\n" .
                               "• A 3-bedroom house in developing areas\n" .
                               "• A small villa in some locations\n\n" .
                               "Let me find houses in your budget!";
                    } else {
                        return $spellingNote . "💰 With a budget of Ksh " . number_format($priceValue) . " (" . $formattedPrice . "), we have:\n\n" .
                               "• Land: Starting from Ksh 500K per acre\n" .
                               "• Houses: Starting from Ksh 5M\n" .
                               "• Apartments: Starting from Ksh 3M\n" .
                               "• Commercial: Starting from Ksh 10M\n\n" .
                               "What type of property are you looking for?";
                    }
                }
                
                // If no abbreviation, continue with regular price queries
                if (strpos($message, 'land') !== false) {
                    return $spellingNote . "🌾 Land prices in Kenya vary by location:\n\n" .
                           "• Nairobi: Ksh 15M - 80M+ per acre\n" .
                           "• Mombasa: Ksh 8M - 40M per acre\n" .
                           "• Kisumu: Ksh 3M - 15M per acre\n" .
                           "• Rural areas: Ksh 500K - 5M per acre\n\n" .
                           "Which area are you interested in?";
                } elseif (strpos($message, 'apartment') !== false) {
                    return $spellingNote . "🏢 Apartment prices:\n\n" .
                           "• 1-bedroom: Ksh 3M - 8M (3M - 8M)\n" .
                           "• 2-bedroom: Ksh 5M - 15M (5M - 15M)\n" .
                           "• 3-bedroom: Ksh 8M - 30M (8M - 30M)\n" .
                           "• Luxury/Penthouse: Ksh 30M - 100M+ (30M - 100M+)\n\n" .
                           "What size are you looking for?";
                } elseif (strpos($message, 'house') !== false || strpos($message, 'villa') !== false) {
                    return $spellingNote . "🏠 House/Villa prices:\n\n" .
                           "• 2-bedroom: Ksh 5M - 12M (5M - 12M)\n" .
                           "• 3-bedroom: Ksh 8M - 25M (8M - 25M)\n" .
                           "• 4-bedroom: Ksh 15M - 50M (15M - 50M)\n" .
                           "• Luxury Villa: Ksh 50M - 150M+ (50M - 150M+)\n\n" .
                           "Are you looking for a specific area?";
                } else {
                    return $spellingNote . "💰 We have properties ranging from Ksh 2M to Ksh 150M+.\n\n" .
                           "Price ranges by type:\n" .
                           "• Land: Ksh 500K - 80M+\n" .
                           "• Houses: Ksh 5M - 150M+\n" .
                           "• Apartments: Ksh 3M - 100M+\n" .
                           "• Commercial: Ksh 10M - 500M+\n\n" .
                           "Tell me your budget and what type of property you're looking for!";
                }
            }
        }
        
        // Check for location queries
        $cityKeywords = [
            'nairobi' => "📍 Nairobi: We have properties in Westlands, Kilimani, Karen, Langata, Kileleshwa, Lavington, Runda, and many other prime neighborhoods.\n\nWhat area of Nairobi interests you?",
            'mombasa' => "🏖️ Mombasa: We offer coastal properties in Nyali, Bamburi, Diani Beach, Shanzu, and other beachfront locations.",
            'kisumu' => "🌅 Kisumu: We have properties in Winam, Milimani, and other lakefront areas.",
            'nakuru' => "🏞️ Nakuru: We have properties in Nakuru town and surrounding areas.",
            'eldoret' => "⛰️ Eldoret: We offer properties in Eldoret town and surrounding areas.",
            'thika' => "🌿 Thika: We have properties in Thika and along the Thika Superhighway corridor.",
            'machakos' => "🏙️ Machakos: We offer properties in Machakos town and surrounding areas."
        ];
        
        foreach ($cityKeywords as $city => $response) {
            if (strpos($message, $city) !== false) {
                return $spellingNote . $response;
            }
        }
        
        if (strpos($message, 'location') !== false || strpos($message, 'area') !== false || strpos($message, 'where') !== false) {
            return $spellingNote . "📍 We serve properties across Kenya, including:\n\n• Nairobi (Westlands, Kilimani, Karen, Langata, Kileleshwa)\n• Mombasa (Nyali, Bamburi, Diani Beach)\n• Kisumu (Winam, Milimani)\n• Nakuru\n• Eldoret\n• Thika\n• Machakos\n\nWhich city or area are you interested in?";
        }
        
        // Check for bedrooms
        if (preg_match('/(\d+)\s*(bed|bedroom|bdrm)/i', $message, $matches)) {
            $bedrooms = $matches[1];
            return $spellingNote . "🛏️ I'll look for properties with {$bedrooms} bedrooms. Please tell me your preferred location and budget to help me find the best options.";
        }
        
        // Check intents from database
        $sql = "SELECT * FROM t_chatbot_intents ORDER BY LENGTH(keywords) DESC";
        $stmt = $db->query($sql);
        $intents = $stmt->fetchAll();
        
        foreach ($intents as $intent) {
            $keywords = array_map('trim', explode(',', strtolower($intent['keywords'])));
            foreach ($keywords as $keyword) {
                if (strpos($message, $keyword) !== false) {
                    $response = $intent['response'];
                    $response = str_replace('{greeting}', getTimeGreeting(), $response);
                    $response = str_replace('{goodbye}', getTimeClosing(), $response);
                    return $spellingNote . $response;
                }
            }
        }
        
        // Default responses
        $defaultResponses = [
            "I'm not sure I understand. Can you please rephrase your question?\n\nYou can ask me about:\n• Properties (land, houses, apartments, commercial)\n• Prices and budgets (e.g., 500K, 1M, 2.5M)\n• Locations across Kenya\n• Buying/selling process\n• Contact information\n\nWhat would you like to know?",
            
            "🤔 I didn't quite get that. Here are some things I can help with:\n\n🏠 Find properties\n💰 Price information (e.g., under 1M, between 500K-2M)\n📍 Location details\n📋 Buying process\n📞 Contact us\n\nWhat can I help you with today?",
            
            "😊 I'm here to help! You can ask me about:\n\n• Available properties for sale\n• Price ranges (try: 100K, 1M, 2.5M)\n• Property locations and neighborhoods\n• The buying and selling process\n• Our contact details\n\nJust type your question and I'll do my best to assist!"
        ];
        
        return $spellingNote . $defaultResponses[array_rand($defaultResponses)];
        
    } catch (Exception $e) {
        error_log("Chatbot Error in getChatbotResponse: " . $e->getMessage());
        return "I apologize, but I'm having trouble processing your request. Please try again later or contact us directly:\n\n📞 Phone: +254 700 123456\n📧 Email: info@triplekproperties.co.ke";
    }
}

function getProperties($limit = null, $featured = false) {
    try {
        $db = Database::getInstance();
        $sql = "SELECT * FROM t_properties WHERE status = 'available'";
        if ($featured) {
            $sql .= " AND featured = 1";
        }
        $sql .= " ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function getPropertyById($id) {
    try {
        $db = Database::getInstance();
        $sql = "SELECT id, title, location, price_ksh, price_usd, property_type, 
                       bedrooms, bathrooms, size_sqft, description, status, 
                       image_url, video_url, created_at, featured 
                FROM t_properties 
                WHERE id = ?";
        $stmt = $db->query($sql, [$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Get property image URL with fallback
 */
function getPropertyImage($property, $default = 'assets/images/default-property.jpg') {
    if (!empty($property['image_url'])) {
        return htmlspecialchars($property['image_url']);
    }
    return $default;
}

/**
 * Check if property has a video
 */
function hasPropertyVideo($property) {
    return !empty($property['video_url']);
}

/**
 * Get property video URL
 */
function getPropertyVideo($property) {
    return !empty($property['video_url']) ? htmlspecialchars($property['video_url']) : null;
}

/**
 * Get property type badge color
 */
function getPropertyTypeColor($type) {
    $colors = [
        'land' => '#38a169',
        'house' => '#2b6cb0',
        'apartment' => '#d69e2e',
        'commercial' => '#e53e3e',
        'villa' => '#805ad5'
    ];
    return $colors[$type] ?? '#4a5568';
}

/**
 * Get property type icon
 */
function getPropertyTypeIcon($type) {
    $icons = [
        'land' => 'fa-mountain',
        'house' => 'fa-home',
        'apartment' => 'fa-building',
        'commercial' => 'fa-store',
        'villa' => 'fa-crown'
    ];
    return $icons[$type] ?? 'fa-building';
}

/**
 * Format property price
 */
function formatPropertyPrice($amount, $currency = 'ksh') {
    if ($currency === 'usd') {
        return '$' . number_format($amount);
    }
    return 'Ksh ' . number_format($amount);
}

/**
 * Get property status badge
 */
function getPropertyStatusBadge($status) {
    $badges = [
        'available' => '<span class="status-badge status-available"><i class="fas fa-check-circle"></i> Available</span>',
        'sold' => '<span class="status-badge status-sold"><i class="fas fa-times-circle"></i> Sold</span>',
        'pending' => '<span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span>'
    ];
    return $badges[$status] ?? $badges['available'];
}

/**
 * Get featured properties (alias for getProperties with featured=true)
 */
function getFeaturedProperties($limit = 6) {
    return getProperties($limit, true);
}

/**
 * Get all property types for filter
 */
function getPropertyTypes() {
    return ['land', 'house', 'apartment', 'commercial', 'villa'];
}

/**
 * Get recent properties
 */
function getRecentProperties($limit = 5) {
    try {
        $db = Database::getInstance();
        $sql = "SELECT * FROM t_properties WHERE status = 'available' ORDER BY created_at DESC LIMIT ?";
        $stmt = $db->query($sql, [$limit]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Search properties by keyword
 */
function searchProperties($keyword, $limit = null) {
    try {
        $db = Database::getInstance();
        $sql = "SELECT * FROM t_properties WHERE status = 'available' 
                AND (title LIKE ? OR location LIKE ? OR description LIKE ?)";
        $params = ["%$keyword%", "%$keyword%", "%$keyword%"];
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $stmt = $db->query($sql, $params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get properties by type
 */
function getPropertiesByType($type, $limit = null) {
    try {
        $db = Database::getInstance();
        $sql = "SELECT * FROM t_properties WHERE status = 'available' AND property_type = ?";
        $params = [$type];
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $stmt = $db->query($sql, $params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get properties within price range
 */
function getPropertiesByPriceRange($min, $max, $limit = null) {
    try {
        $db = Database::getInstance();
        $sql = "SELECT * FROM t_properties WHERE status = 'available' AND price_ksh BETWEEN ? AND ?";
        $params = [$min, $max];
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $stmt = $db->query($sql, $params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function getAboutContent() {
    try {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM t_about LIMIT 1");
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

function getTestimonials($approved = true) {
    try {
        $db = Database::getInstance();
        $sql = "SELECT * FROM t_testimonials";
        if ($approved) {
            $sql .= " WHERE is_approved = 1";
        }
        $sql .= " ORDER BY created_at DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function getFaqs($category = null) {
    try {
        $db = Database::getInstance();
        $sql = "SELECT * FROM t_faq";
        if ($category) {
            $sql .= " WHERE category = ?";
        }
        $sql .= " ORDER BY created_at ASC";
        $stmt = $db->query($sql, $category ? [$category] : []);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function saveContact($data) {
    try {
        $db = Database::getInstance();
        $sql = "INSERT INTO t_contact (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
        return $db->query($sql, [
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['subject'] ?? null,
            $data['message']
        ]);
    } catch (Exception $e) {
        return false;
    }
}

function saveChatMessage($sessionId, $userMessage, $botResponse, $email = null, $phone = null) {
    try {
        $db = Database::getInstance();
        $sql = "INSERT INTO t_chatbot_conversations (session_id, user_message, bot_response, user_email, user_phone) VALUES (?, ?, ?, ?, ?)";
        return $db->query($sql, [$sessionId, $userMessage, $botResponse, $email, $phone]);
    } catch (Exception $e) {
        return false;
    }
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generateSessionId() {
    return session_id() ?: bin2hex(random_bytes(16));
}

function formatPrice($amount, $currency = 'ksh') {
    if ($currency === 'usd') {
        return '$' . number_format($amount);
    }
    return 'Ksh ' . number_format($amount);
}
?>