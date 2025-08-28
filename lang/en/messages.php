<?php

/**
 * @link https://laravel.com/docs/11.x/localization#introduction
 */

return [
    // Resource titles
    'order.title' => 'Order|Orders',
    'product.title' => 'Product|Products',
    'distributor.title' => 'Distributor|Distributors',

    // Order fields and labels
    'order' => [
        'order_number' => 'Order Number',
        'distributor' => 'Distributor',
        'status' => 'Status',
        'order_date' => 'Order Date',
        'delivery_date' => 'Delivery Date',
        'total_amount' => 'Total Amount',
        'notes' => 'Notes',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',

        // Order status values
        'status_pending' => 'Pending',
        'status_processing' => 'Processing',
        'status_shipped' => 'Shipped',
        'status_delivered' => 'Delivered',
        'status_cancelled' => 'Cancelled',

        // Order items
        'items' => 'Items',
        'product' => 'Product',
        'quantity' => 'Quantity',
        'unit_price' => 'Unit Price',
        'total_price' => 'Total Price',

        // Form sections
        'information' => 'Order Information',
        'distributor_information' => 'Distributor Information',
        'order_items' => 'Order Items',
        'additional_information' => 'Additional Information',

        // Actions
        'add_product' => 'Add Product',
        'select_distributor' => 'Select Distributor',
        'create_new_distributor' => 'Create New Distributor',
        'select_product' => 'Select Product',
        'create_new_product' => 'Create New Product',

        // Helper texts
        'select_distributor_help' => 'Select an existing distributor or create a new one',
        'order_items_help' => 'Add products to this order',

        // Navigation and pages
        'navigation_label' => 'Orders',
        'create_title' => 'Create New Order',
        'edit_title' => 'Edit Order',
        'view_title' => 'View Order',
        'list_title' => 'All Orders',
    ],

    // Product fields and labels
    'product' => [
        'name' => 'Product Name',
        'sku' => 'SKU',
        'ean' => 'EAN/GTIN',
        'description' => 'Description',
        'image' => 'Product Image',
        'price' => 'Price',
        'current_price' => 'Current Price',
        'average_price' => 'Average Price',
        'avg_price' => 'Avg Price',
        'stock_quantity' => 'Stock Quantity',
        'stock' => 'Stock',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',

        // Form sections and helper texts
        'product_information' => 'Product Information',
        'pricing_inventory' => 'Pricing & Inventory',
        'media' => 'Media',

        // Helper texts
        'ean_help' => 'European Article Number or Global Trade Item Number',
        'image_help' => 'Upload product image (max 5MB, JPEG/PNG/WebP)',
        'price_help' => 'Sale price in Euro',
        'stock_help' => 'Available quantity in stock',

        // Navigation and pages
        'navigation_label' => 'Products',
        'create_title' => 'Create New Product',
        'edit_title' => 'Edit Product',
        'view_title' => 'View Product',
        'list_title' => 'All Products',
    ],

    // Distributor fields and labels
    'distributor' => [
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'website' => 'Website',
        'company' => 'Company',
        'address' => 'Address',
        'notes' => 'Notes',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',
        'full_name' => 'Full Name',

        // Form sections
        'distributor_information' => 'Distributor Information',
        'contact_information' => 'Contact Information',
        'address_notes' => 'Address & Notes',
        'additional_information' => 'Additional Information',

        // Helper texts
        'website_help' => 'Distributor website URL',
        'phone_help' => 'Distributor phone number',
        'company_help' => 'Distributor company name',
        'address_help' => 'Full distributor address',
        'notes_help' => 'Additional notes about the distributor',

        // Placeholders
        'website_placeholder' => 'https://example.com',
        'email_placeholder' => 'contact@example.com',

        // Navigation and pages
        'navigation_label' => 'Distributors',
        'create_title' => 'Create New Distributor',
        'edit_title' => 'Edit Distributor',
        'view_title' => 'View Distributor',
        'list_title' => 'All Distributors',
    ],

    // Common field labels that might be used across resources
    'fields' => [
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'website' => 'Website',
        'address' => 'Address',
        'company' => 'Company',
        'notes' => 'Notes',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',
    ],

    // Common actions
    'actions' => [
        'create' => 'Create',
        'edit' => 'Edit',
        'view' => 'View',
        'delete' => 'Delete',
        'restore' => 'Restore',
        'force_delete' => 'Force Delete',
        'export' => 'Export',
        'import' => 'Import',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'reorder' => 'Reorder',
    ],

    // Widget translations
    'widgets' => [
        // Inventory Overview Widget
        'inventory_overview' => [
            'total_products' => 'Total Products',
            'total_products_desc' => 'All products in catalog',
            'inventory_value' => 'Inventory Value',
            'inventory_value_desc' => 'Total stock value',
            'low_stock_items' => 'Low Stock Items',
            'low_stock_items_desc' => 'Less than 10 units',
            'out_of_stock' => 'Out of Stock',
            'out_of_stock_desc' => 'Items needing reorder',
        ],
        
        // Low Stock Alerts Widget
        'low_stock_alerts' => [
            'title' => 'Low Stock Alerts',
            'empty_title' => 'No Low Stock Items',
            'empty_description' => 'All products have adequate stock levels.',
            'reorder_action' => 'Reorder',
        ],
        
        // Recent Orders Widget
        'recent_orders' => [
            'title' => 'Recent Orders',
            'empty_title' => 'No Recent Orders',
            'empty_description' => 'Orders you create will appear here.',
            'view_action' => 'View',
            'created_label' => 'Created',
        ],
        
        // Monthly Spending Chart
        'monthly_spending' => [
            'title' => 'Monthly Spending',
            'chart_label' => 'Monthly Spending (€)',
        ],
        
        // Product Price History Chart
        'price_history' => [
            'title' => 'Price Development',
            'description' => 'Historical price changes across different orders and distributors',
            'chart_label' => 'Unit Price (€)',
        ],
        
        // Product Order History Widget
        'order_history' => [
            'title' => 'Order History',
            'empty_title' => 'No Orders Found',
            'empty_description' => 'This product has not been ordered yet.',
            'order_number' => 'Order Number',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'total_price' => 'Total Price',
            'order_date' => 'Order Date',
            'status' => 'Status',
        ],
    ],
];
