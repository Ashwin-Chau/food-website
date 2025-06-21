<?php
// Sorting function (supports user-specified columns)
function sortItems($items, $sort, $order) {
    usort($items, function($a, $b) use ($sort, $order) {
        $val_a = $a[$sort] ?? '';
        $val_b = $b[$sort] ?? '';
        // Handle order_time sorting
        if ($sort === 'order_time') {
            try {
                $val_a = DateTime::createFromFormat('Y-m-d H:i:s', $a['created_at'])->format('H:i:s');
                $val_b = DateTime::createFromFormat('Y-m-d H:i:s', $b['created_at'])->format('H:i:s');
            } catch (Exception $e) {
                $val_a = '';
                $val_b = '';
            }
            $cmp = strcmp($val_a, $val_b);
        }
        // Handle numeric and date fields
        elseif (in_array($sort, ['id', 'price', 'quantity', 'status', 'role_as', 'total_price', 'total_sales', 'order_count', 'quantity_sold', 'total_revenue'])) {
            $val_a = floatval($val_a);
            $val_b = floatval($val_b);
            $cmp = ($val_a <=> $val_b);
        } elseif ($sort === 'created_at' || $sort === 'period') {
            $val_a = strtotime($val_a);
            $val_b = strtotime($val_b);
            $cmp = ($val_a <=> $val_b);
        } else {
            $val_a = strtolower((string)$val_a);
            $val_b = strtolower((string)$val_b);
            $cmp = ($val_a <=> $val_b);
        }
        return $order === 'ASC' ? $cmp : -$cmp;
    });
    return $items;
}

// FCFS sorting function (sorts by created_at ascending)
function fcfsSort($items) {
    usort($items, function($a, $b) {
        $val_a = strtotime($a['created_at'] ?? '');
        $val_b = strtotime($b['created_at'] ?? '');
        return $val_a <=> $val_b; // Ascending order (first-come-first-serve)
    });
    return $items;
}

// Binary search function for string fields (configurable fields)
function binarySearchItems($items, $search, $fields = ['name']) {
    $filtered_items = [];
    $search = trim($search);

    // If the search term is numeric, try an exact match for ID first
    if (is_numeric($search)) {
        foreach ($items as $item) {
            if (isset($item['id']) && (string)$item['id'] === $search) {
                $filtered_items[] = $item;
                break; // ID is unique
            }
        }
    }

    // Search string fields
    if (empty($filtered_items)) {
        foreach ($fields as $field) {
            $sorted = sortItems($items, $field, 'ASC');
            $left = 0;
            $right = count($sorted) - 1;

            while ($left <= $right) {
                $mid = floor(($left + $right) / 2);
                $value = strtolower((string)($sorted[$mid][$field] ?? ''));

                if (strpos($value, strtolower($search)) !== false) {
                    $filtered_items[] = $sorted[$mid];

                    // Search left side for additional matches
                    for ($i = $mid - 1; $i >= 0 && strpos(strtolower($sorted[$i][$field] ?? ''), strtolower($search)) !== false; $i--) {
                        $filtered_items[] = $sorted[$i];
                    }

                    // Search right side for additional matches
                    for ($i = $mid + 1; $i < count($sorted) && strpos(strtolower($sorted[$i][$field] ?? ''), strtolower($search)) !== false; $i++) {
                        $filtered_items[] = $sorted[$i];
                    }

                    break; // One match set per field
                } elseif ($value < $search) {
                    $left = $mid + 1;
                } else {
                    $right = $mid - 1;
                }
            }
        }
    }

    // Remove duplicates based on ID
    $unique_items = [];
    $seen_ids = [];
    foreach ($filtered_items as $item) {
        if (!in_array($item['id'], $seen_ids)) {
            $unique_items[] = $item;
            $seen_ids[] = $item['id'];
        }
    }

    return $unique_items;
}

// Binary search function for ID only
function binarySearchItemsById($items, $search) {
    $filtered_items = [];
    $search = trim($search);

    // Validate search term as a numeric value for ID
    $searchId = filter_var($search, FILTER_VALIDATE_INT);
    if ($searchId === false) {
        return $items; // Return all items if search is not a valid integer
    }

    // Sort items by ID
    $sorted_items = $items;
    usort($sorted_items, function($a, $b) {
        return floatval($a['id']) <=> floatval($b['id']);
    });

    $left = 0;
    $right = count($sorted_items) - 1;

    while ($left <= $right) {
        $mid = (int)(($left + $right) / 2);
        $itemId = floatval($sorted_items[$mid]['id']);

        if ($itemId == $searchId) {
            $filtered_items[] = $sorted_items[$mid];

            // Check adjacent items for same ID (unlikely)
            $i = $mid - 1;
            while ($i >= 0 && floatval($sorted_items[$i]['id']) == $searchId) {
                $filtered_items[] = $sorted_items[$i];
                $i--;
            }
            $i = $mid + 1;
            while ($i < count($sorted_items) && floatval($sorted_items[$i]['id']) == $searchId) {
                $filtered_items[] = $sorted_items[$i];
                $i++;
            }

            return $filtered_items;
        } elseif ($itemId < $searchId) {
            $left = $mid + 1;
        } else {
            $right = $mid - 1;
        }
    }

    return [];
}
?>