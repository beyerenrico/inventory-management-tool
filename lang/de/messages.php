<?php

/**
 * @link https://laravel.com/docs/11.x/localization#introduction
 */

return [
    // Resource titles
    'order.title' => 'Bestellung|Bestellungen',
    'product.title' => 'Produkt|Produkte',
    'distributor.title' => 'Händler|Händler',

    // Order fields and labels
    'order' => [
        'order_number' => 'Bestellnummer',
        'distributor' => 'Händler',
        'status' => 'Status',
        'order_date' => 'Bestelldatum',
        'delivery_date' => 'Lieferdatum',
        'total_amount' => 'Gesamtbetrag',
        'notes' => 'Notizen',
        'created_at' => 'Erstellt am',
        'updated_at' => 'Aktualisiert am',
        'deleted_at' => 'Gelöscht am',
        
        // Order status values
        'status_pending' => 'Ausstehend',
        'status_processing' => 'In Bearbeitung',
        'status_shipped' => 'Versandt',
        'status_delivered' => 'Geliefert',
        'status_cancelled' => 'Storniert',
        
        // Order items
        'items' => 'Positionen',
        'product' => 'Produkt',
        'quantity' => 'Menge',
        'unit_price' => 'Einzelpreis',
        'total_price' => 'Gesamtpreis',
        
        // Form sections
        'information' => 'Bestellinformationen',
        'distributor_information' => 'Händlerinformationen',
        'order_items' => 'Bestellpositionen',
        'additional_information' => 'Zusätzliche Informationen',
        
        // Actions
        'add_product' => 'Produkt hinzufügen',
        'select_distributor' => 'Händler auswählen',
        'create_new_distributor' => 'Neuen Händler erstellen',
        'select_product' => 'Produkt auswählen',
        'create_new_product' => 'Neues Produkt erstellen',
        
        // Helper texts
        'select_distributor_help' => 'Wählen Sie einen vorhandenen Händler aus oder erstellen Sie einen neuen',
        'order_items_help' => 'Fügen Sie Produkte zu dieser Bestellung hinzu',
        
        // Navigation and pages
        'navigation_label' => 'Bestellungen',
        'create_title' => 'Neue Bestellung erstellen',
        'edit_title' => 'Bestellung bearbeiten',
        'view_title' => 'Bestellung anzeigen',
        'list_title' => 'Alle Bestellungen',
    ],

    // Product fields and labels
    'product' => [
        'name' => 'Produktname',
        'sku' => 'SKU',
        'ean' => 'EAN/GTIN', 
        'description' => 'Beschreibung',
        'image' => 'Produktbild',
        'price' => 'Preis',
        'current_price' => 'Aktueller Preis',
        'average_price' => 'Durchschnittspreis',
        'avg_price' => 'Ø Preis',
        'stock_quantity' => 'Lagerbestand',
        'stock' => 'Lager',
        'created_at' => 'Erstellt am',
        'updated_at' => 'Aktualisiert am',
        'deleted_at' => 'Gelöscht am',
        
        // Form sections and helper texts
        'product_information' => 'Produktinformationen',
        'pricing_inventory' => 'Preise & Lagerbestand',
        'media' => 'Medien',
        
        // Helper texts
        'ean_help' => 'Europäische Artikelnummer oder Global Trade Item Number',
        'image_help' => 'Produktbild hochladen (max 5MB, JPEG/PNG/WebP)',
        'price_help' => 'Verkaufspreis in Euro',
        'stock_help' => 'Verfügbare Menge im Lager',
        
        // Navigation and pages
        'navigation_label' => 'Produkte',
        'create_title' => 'Neues Produkt erstellen',
        'edit_title' => 'Produkt bearbeiten',
        'view_title' => 'Produkt anzeigen',
        'list_title' => 'Alle Produkte',
    ],

    // Distributor fields and labels
    'distributor' => [
        'name' => 'Name',
        'email' => 'E-Mail',
        'phone' => 'Telefon',
        'website' => 'Website',
        'company' => 'Unternehmen',
        'address' => 'Adresse',
        'notes' => 'Notizen',
        'created_at' => 'Erstellt am',
        'updated_at' => 'Aktualisiert am',
        'deleted_at' => 'Gelöscht am',
        'full_name' => 'Vollständiger Name',
        
        // Form sections
        'distributor_information' => 'Händlerinformationen',
        'contact_information' => 'Kontaktinformationen',
        'address_notes' => 'Adresse & Notizen',
        'additional_information' => 'Zusätzliche Informationen',
        
        // Helper texts
        'website_help' => 'Website-URL des Händlers',
        'phone_help' => 'Telefonnummer des Händlers',
        'company_help' => 'Firmenname des Händlers',
        'address_help' => 'Vollständige Adresse des Händlers',
        'notes_help' => 'Zusätzliche Notizen zum Händler',
        
        // Placeholders
        'website_placeholder' => 'https://beispiel.de',
        'email_placeholder' => 'kontakt@beispiel.de',
        
        // Navigation and pages
        'navigation_label' => 'Händler',
        'create_title' => 'Neuen Händler erstellen',
        'edit_title' => 'Händler bearbeiten',
        'view_title' => 'Händler anzeigen',
        'list_title' => 'Alle Händler',
    ],

    // Common field labels that might be used across resources
    'fields' => [
        'name' => 'Name',
        'email' => 'E-Mail',
        'phone' => 'Telefon',
        'website' => 'Website',
        'address' => 'Adresse',
        'company' => 'Unternehmen',
        'notes' => 'Notizen',
        'created_at' => 'Erstellt am',
        'updated_at' => 'Aktualisiert am',
        'deleted_at' => 'Gelöscht am',
    ],

    // Common actions
    'actions' => [
        'create' => 'Erstellen',
        'edit' => 'Bearbeiten',
        'view' => 'Anzeigen',
        'delete' => 'Löschen',
        'restore' => 'Wiederherstellen',
        'force_delete' => 'Endgültig löschen',
        'export' => 'Exportieren',
        'import' => 'Importieren',
        'save' => 'Speichern',
        'cancel' => 'Abbrechen',
        'reorder' => 'Nachbestellen',
    ],

    // Widget translations
    'widgets' => [
        // Inventory Overview Widget
        'inventory_overview' => [
            'total_products' => 'Produkte Gesamt',
            'total_products_desc' => 'Alle Produkte im Katalog',
            'inventory_value' => 'Lagerwert',
            'inventory_value_desc' => 'Gesamtwert des Lagers',
            'low_stock_items' => 'Niedrigbestand',
            'low_stock_items_desc' => 'Weniger als 10 Stück',
            'out_of_stock' => 'Nicht vorrätig',
            'out_of_stock_desc' => 'Artikel die nachbestellt werden müssen',
        ],
        
        // Low Stock Alerts Widget
        'low_stock_alerts' => [
            'title' => 'Niedrigbestand-Alarme',
            'empty_title' => 'Keine Niedrigbestand-Artikel',
            'empty_description' => 'Alle Produkte haben ausreichende Lagerbestände.',
            'reorder_action' => 'Nachbestellen',
        ],
        
        // Recent Orders Widget
        'recent_orders' => [
            'title' => 'Aktuelle Bestellungen',
            'empty_title' => 'Keine aktuellen Bestellungen',
            'empty_description' => 'Erstellte Bestellungen werden hier angezeigt.',
            'view_action' => 'Anzeigen',
            'created_label' => 'Erstellt',
        ],
        
        // Monthly Spending Chart
        'monthly_spending' => [
            'title' => 'Monatliche Ausgaben',
            'chart_label' => 'Monatliche Ausgaben (€)',
        ],
        
        // Product Price History Chart
        'price_history' => [
            'title' => 'Preisentwicklung',
            'description' => 'Historische Preisänderungen über verschiedene Bestellungen und Händler',
            'chart_label' => 'Stückpreis (€)',
        ],
        
        // Product Order History Widget
        'order_history' => [
            'title' => 'Bestellhistorie',
            'empty_title' => 'Keine Bestellungen gefunden',
            'empty_description' => 'Dieses Produkt wurde noch nicht bestellt.',
            'order_number' => 'Bestellnummer',
            'quantity' => 'Menge',
            'unit_price' => 'Stückpreis',
            'total_price' => 'Gesamtpreis',
            'order_date' => 'Bestelldatum',
            'status' => 'Status',
        ],
    ],
];