<?php
/**
 * Demo WooCommerce product seeder.
 *
 * Usage (while logged in as admin):
 * /wp-admin/?noble_seed_demo_products=1
 *
 * @package noble-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Count products by SKU prefix.
 *
 * @param string $sku_prefix SKU prefix.
 * @return int
 */
function noble_count_products_by_sku_prefix( $sku_prefix ) {
	$query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'private', 'draft' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => '_sku',
					'value'   => $sku_prefix,
					'compare' => 'LIKE',
				),
			),
		)
	);

	return (int) $query->found_posts;
}

/**
 * Build and assign local WooCommerce attributes.
 *
 * @param array<string,string> $pairs Attribute label => value.
 * @return array<int,WC_Product_Attribute>
 */
function noble_build_product_attributes( $pairs ) {
	$attributes = array();

	foreach ( $pairs as $label => $value ) {
		$attribute = new WC_Product_Attribute();
		$attribute->set_id( 0 );
		$attribute->set_name( $label );
		$attribute->set_options( array( $value ) );
		$attribute->set_visible( true );
		$attribute->set_variation( false );
		$attributes[] = $attribute;
	}

	return $attributes;
}

/**
 * Seed 20 demo products for development usage.
 */
function noble_seed_demo_products() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$seed_simple   = isset( $_GET['noble_seed_demo_products'] ) && '1' === (string) $_GET['noble_seed_demo_products'];
	$seed_variable = isset( $_GET['noble_seed_variable_products'] ) && '1' === (string) $_GET['noble_seed_variable_products'];
	$seed_quiz     = isset( $_GET['noble_seed_quiz_products'] ) && '1' === (string) $_GET['noble_seed_quiz_products'];
	$force_seed    = isset( $_GET['noble_seed_force'] ) && '1' === (string) $_GET['noble_seed_force'];

	if ( ! $seed_simple && ! $seed_variable && ! $seed_quiz ) {
		return;
	}

	if ( ! class_exists( 'WooCommerce' ) ) {
		wp_die( esc_html__( 'WooCommerce is not active.', 'noble-theme' ) );
	}

	$existing_simple_products   = noble_count_products_by_sku_prefix( 'NOBLE-DEMO-' );
	$existing_variable_products = noble_count_products_by_sku_prefix( 'NOBLE-VAR-' );
	$existing_quiz_products     = noble_count_products_by_sku_prefix( 'NOBLE-QUIZ-' );

	if ( $seed_simple && get_option( 'noble_demo_products_seeded' ) && ! $force_seed && $existing_simple_products > 0 ) {
		wp_die( esc_html__( 'Demo products already seeded. Delete option noble_demo_products_seeded to run again.', 'noble-theme' ) );
	}
	if ( $seed_variable && get_option( 'noble_demo_variable_products_seeded' ) && ! $force_seed && $existing_variable_products > 0 ) {
		wp_die( esc_html__( 'Variable demo products already seeded. Delete option noble_demo_variable_products_seeded to run again.', 'noble-theme' ) );
	}
	if ( $seed_quiz && get_option( 'noble_quiz_products_seeded' ) && ! $force_seed && $existing_quiz_products > 0 ) {
		wp_die( esc_html__( 'Quiz demo products already seeded. Delete option noble_quiz_products_seeded to run again.', 'noble-theme' ) );
	}

	$category_map = array(
		'خنک و دریایی'   => 'khanak-daryayi',
		'گرم و شرقی'     => 'garm-sharghi',
		'روزانه و اداری' => 'roozane-edari',
		'لوکس و خاص'     => 'lux-special',
	);

	$category_ids = array();
	foreach ( $category_map as $cat_name => $cat_slug ) {
		$term = term_exists( $cat_slug, 'product_cat' );
		if ( ! $term ) {
			$term = wp_insert_term(
				$cat_name,
				'product_cat',
				array(
					'slug' => $cat_slug,
				)
			);
		}
		if ( ! is_wp_error( $term ) && ! empty( $term['term_id'] ) ) {
			$category_ids[ $cat_name ] = (int) $term['term_id'];
		}
	}

	$products = array(
		array( 'name' => 'دکانت آکوا دی نوبل', 'brand' => 'NOBLE SIGNATURE', 'category' => 'خنک و دریایی', 'price' => 1250000, 'sale' => 990000, 'volume' => '30ml', 'gender' => 'Unisex', 'family' => 'Aquatic', 'season' => 'بهار/تابستان', 'stock' => 18 ),
		array( 'name' => 'پرفیوم آبی آتلانتیک', 'brand' => 'BLUE ATLAS', 'category' => 'خنک و دریایی', 'price' => 2150000, 'sale' => 0, 'volume' => '100ml', 'gender' => 'Men', 'family' => 'Citrus Marine', 'season' => 'تابستان', 'stock' => 14 ),
		array( 'name' => 'رز میدنایت نوبل', 'brand' => 'NOBLE SIGNATURE', 'category' => 'لوکس و خاص', 'price' => 3950000, 'sale' => 3290000, 'volume' => '75ml', 'gender' => 'Women', 'family' => 'Floral Oriental', 'season' => 'پاییز/زمستان', 'stock' => 9 ),
		array( 'name' => 'عود امرالد پرایوت', 'brand' => 'PRIVATE BLEND', 'category' => 'گرم و شرقی', 'price' => 4750000, 'sale' => 4190000, 'volume' => '100ml', 'gender' => 'Unisex', 'family' => 'Oud Spicy', 'season' => 'زمستان', 'stock' => 7 ),
		array( 'name' => 'ونیل کرست الیت', 'brand' => 'ELITE EDIT', 'category' => 'گرم و شرقی', 'price' => 2890000, 'sale' => 0, 'volume' => '90ml', 'gender' => 'Women', 'family' => 'Vanilla Amber', 'season' => 'پاییز', 'stock' => 11 ),
		array( 'name' => 'سدر نایت کد', 'brand' => 'ORIGINAL EDIT', 'category' => 'روزانه و اداری', 'price' => 2490000, 'sale' => 2190000, 'volume' => '100ml', 'gender' => 'Men', 'family' => 'Woody Aromatic', 'season' => 'چهارفصل', 'stock' => 20 ),
		array( 'name' => 'ایریس وایت ساین', 'brand' => 'WHITE LABEL', 'category' => 'لوکس و خاص', 'price' => 3650000, 'sale' => 0, 'volume' => '80ml', 'gender' => 'Women', 'family' => 'Powdery Floral', 'season' => 'بهار', 'stock' => 12 ),
		array( 'name' => 'اسموک اند امبر', 'brand' => 'LIMITED SELECT', 'category' => 'گرم و شرقی', 'price' => 4290000, 'sale' => 3750000, 'volume' => '100ml', 'gender' => 'Unisex', 'family' => 'Smoky Amber', 'season' => 'پاییز/زمستان', 'stock' => 10 ),
		array( 'name' => 'سیلک جاسمین', 'brand' => 'FLORA PRIVATE', 'category' => 'لوکس و خاص', 'price' => 3120000, 'sale' => 2790000, 'volume' => '75ml', 'gender' => 'Women', 'family' => 'White Floral', 'season' => 'بهار/تابستان', 'stock' => 15 ),
		array( 'name' => 'نوبل دِی لایت', 'brand' => 'NOBLE SIGNATURE', 'category' => 'روزانه و اداری', 'price' => 1790000, 'sale' => 0, 'volume' => '50ml', 'gender' => 'Unisex', 'family' => 'Fresh Citrus', 'season' => 'چهارفصل', 'stock' => 25 ),
		array( 'name' => 'تنباکو نویر', 'brand' => 'BLACK EDITION', 'category' => 'گرم و شرقی', 'price' => 3990000, 'sale' => 3490000, 'volume' => '100ml', 'gender' => 'Men', 'family' => 'Tobacco Sweet', 'season' => 'زمستان', 'stock' => 8 ),
		array( 'name' => 'موسک کلین رز', 'brand' => 'PURE NOTE', 'category' => 'روزانه و اداری', 'price' => 2050000, 'sale' => 1890000, 'volume' => '85ml', 'gender' => 'Unisex', 'family' => 'Musk Clean', 'season' => 'چهارفصل', 'stock' => 17 ),
		array( 'name' => 'گلدن زعفران', 'brand' => 'ORIENTAL CODE', 'category' => 'لوکس و خاص', 'price' => 4550000, 'sale' => 0, 'volume' => '100ml', 'gender' => 'Unisex', 'family' => 'Saffron Amber', 'season' => 'پاییز/زمستان', 'stock' => 6 ),
		array( 'name' => 'اسپلاش سی بریز', 'brand' => 'BLUE ATLAS', 'category' => 'خنک و دریایی', 'price' => 1980000, 'sale' => 1690000, 'volume' => '100ml', 'gender' => 'Men', 'family' => 'Marine Fresh', 'season' => 'تابستان', 'stock' => 13 ),
		array( 'name' => 'پیونی بلَش', 'brand' => 'FLORA PRIVATE', 'category' => 'روزانه و اداری', 'price' => 2380000, 'sale' => 0, 'volume' => '75ml', 'gender' => 'Women', 'family' => 'Floral Fruity', 'season' => 'بهار', 'stock' => 19 ),
		array( 'name' => 'اپر وود سوئیت', 'brand' => 'WOOD LAB', 'category' => 'گرم و شرقی', 'price' => 3220000, 'sale' => 2850000, 'volume' => '90ml', 'gender' => 'Men', 'family' => 'Woody Sweet', 'season' => 'پاییز', 'stock' => 12 ),
		array( 'name' => 'امبر کاسمیک', 'brand' => 'LIMITED SELECT', 'category' => 'لوکس و خاص', 'price' => 4890000, 'sale' => 4390000, 'volume' => '100ml', 'gender' => 'Unisex', 'family' => 'Amber Resinous', 'season' => 'زمستان', 'stock' => 5 ),
		array( 'name' => 'مینت سیلور', 'brand' => 'FRESH WORKS', 'category' => 'خنک و دریایی', 'price' => 1860000, 'sale' => 1590000, 'volume' => '100ml', 'gender' => 'Unisex', 'family' => 'Mint Aromatic', 'season' => 'تابستان', 'stock' => 16 ),
		array( 'name' => 'بلک پپر ریزرو', 'brand' => 'PRIVATE BLEND', 'category' => 'گرم و شرقی', 'price' => 3580000, 'sale' => 3290000, 'volume' => '90ml', 'gender' => 'Men', 'family' => 'Spicy Woody', 'season' => 'پاییز/زمستان', 'stock' => 10 ),
		array( 'name' => 'نوبل اسکای', 'brand' => 'NOBLE SIGNATURE', 'category' => 'خنک و دریایی', 'price' => 2290000, 'sale' => 0, 'volume' => '100ml', 'gender' => 'Unisex', 'family' => 'Citrus Aromatic', 'season' => 'بهار/تابستان', 'stock' => 22 ),
	);

	$simple_created   = 0;
	$variable_created = 0;
	$quiz_created     = 0;

	/**
	 * Assign global (taxonomy-based) product attributes (pa_*).
	 *
	 * @param int                 $product_id Product ID.
	 * @param array<string,array> $tax_to_terms taxonomy => term names (string[]) or term slugs (string[]).
	 * @param array<string,bool>  $variation_flags taxonomy => is_variation.
	 * @return void
	 */
	$assign_global_attrs = function( $product_id, $tax_to_terms, $variation_flags = array() ) {
		if ( ! function_exists( 'wc_attribute_taxonomy_id_by_name' ) ) {
			return;
		}

		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return;
		}

		$attributes = $product->get_attributes();

		foreach ( $tax_to_terms as $taxonomy => $terms ) {
			$taxonomy = (string) $taxonomy;
			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			$term_ids = array();
			foreach ( (array) $terms as $term_item ) {
				$term_name = '';
				$term_slug = '';

				if ( is_array( $term_item ) ) {
					$term_name = isset( $term_item['name'] ) ? (string) $term_item['name'] : '';
					$term_slug = isset( $term_item['slug'] ) ? (string) $term_item['slug'] : '';
				} else {
					$term_name = (string) $term_item;
				}

				if ( '' === trim( $term_name ) && '' === trim( $term_slug ) ) {
					continue;
				}

				$lookup = '' !== $term_slug ? $term_slug : $term_name;
				$term   = term_exists( $lookup, $taxonomy );
				if ( ! $term ) {
					$args     = array();
					if ( '' !== $term_slug ) {
						$args['slug'] = $term_slug;
					}
					$inserted = wp_insert_term( '' !== $term_name ? $term_name : $term_slug, $taxonomy, $args );
					if ( is_wp_error( $inserted ) ) {
						continue;
					}
					$term_ids[] = (int) $inserted['term_id'];
				} else {
					$term_ids[] = (int) ( is_array( $term ) ? $term['term_id'] : $term );
				}
			}

			$term_ids = array_values( array_unique( array_filter( $term_ids ) ) );
			if ( empty( $term_ids ) ) {
				continue;
			}

			wp_set_object_terms( $product_id, $term_ids, $taxonomy, false );

			$attr = new WC_Product_Attribute();
			// wc_attribute_taxonomy_id_by_name expects attribute name (without pa_ prefix).
			$attr_name = preg_replace( '/^pa_/', '', $taxonomy );
			$attr->set_id( (int) wc_attribute_taxonomy_id_by_name( $attr_name ) );
			$attr->set_name( $taxonomy );
			$attr->set_options( $term_ids );
			$attr->set_visible( true );
			$attr->set_variation( ! empty( $variation_flags[ $taxonomy ] ) );

			$attributes[ $taxonomy ] = $attr;
		}

		$product->set_attributes( $attributes );
		$product->save();
	};

	if ( $seed_quiz ) {
		// Create a small set of products that definitely contain the 5 quiz attributes.
		$quiz_tax = array(
			'pa_gender',
			'pa_longevity',
			'pa_occasion',
			'pa_scent-family',
			'pa_season',
		);

		$all_exist = true;
		foreach ( $quiz_tax as $tx ) {
			if ( ! taxonomy_exists( $tx ) ) {
				$all_exist = false;
				break;
			}
		}
		if ( ! $all_exist ) {
			wp_die( esc_html__( 'Quiz attributes taxonomies not found (expected pa_gender, pa_longevity, pa_occasion, pa_scent-family, pa_season). Create them in WooCommerce > Attributes first.', 'noble-theme' ) );
		}

		// Ensure predictable latin slugs so Woo filters work reliably.
		$quiz_term_catalog = array(
			'pa_gender' => array(
				array( 'name' => 'مردانه', 'slug' => 'men' ),
				array( 'name' => 'زنانه', 'slug' => 'women' ),
				array( 'name' => 'یونیسکس', 'slug' => 'unisex' ),
			),
			'pa_longevity' => array(
				array( 'name' => 'کم', 'slug' => 'low' ),
				array( 'name' => 'متوسط', 'slug' => 'medium' ),
				array( 'name' => 'بالا', 'slug' => 'high' ),
				array( 'name' => 'خیلی بالا', 'slug' => 'very-high' ),
			),
			'pa_occasion' => array(
				array( 'name' => 'روزانه', 'slug' => 'daily' ),
				array( 'name' => 'اداری', 'slug' => 'office' ),
				array( 'name' => 'مهمانی', 'slug' => 'party' ),
				array( 'name' => 'قرار', 'slug' => 'date' ),
				array( 'name' => 'اسپرت', 'slug' => 'sport' ),
			),
			'pa_scent-family' => array(
				array( 'name' => 'شرقی', 'slug' => 'oriental' ),
				array( 'name' => 'گلی', 'slug' => 'floral' ),
				array( 'name' => 'چوبی', 'slug' => 'woody' ),
				array( 'name' => 'خنک', 'slug' => 'fresh' ),
				array( 'name' => 'میوه‌ای', 'slug' => 'fruity' ),
				array( 'name' => 'آروماتیک', 'slug' => 'aromatic' ),
			),
			'pa_season' => array(
				array( 'name' => 'بهار', 'slug' => 'spring' ),
				array( 'name' => 'تابستان', 'slug' => 'summer' ),
				array( 'name' => 'پاییز', 'slug' => 'autumn' ),
				array( 'name' => 'زمستان', 'slug' => 'winter' ),
				array( 'name' => 'چهارفصل', 'slug' => 'all-season' ),
			),
		);

		foreach ( $quiz_term_catalog as $tax => $items ) {
			foreach ( $items as $it ) {
				$exists = term_exists( (string) $it['slug'], (string) $tax );
				if ( ! $exists ) {
					wp_insert_term( (string) $it['name'], (string) $tax, array( 'slug' => (string) $it['slug'] ) );
				}
			}
		}

		// Cleanup old Persian/legacy terms on existing quiz products so only latin slugs remain.
		$quiz_products = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => array( 'publish', 'private', 'draft' ),
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => '_sku',
						'value'   => 'NOBLE-QUIZ-',
						'compare' => 'LIKE',
					),
				),
			)
		);

		if ( ! empty( $quiz_products ) ) {
			$catalog_name_to_slug = array();
			foreach ( $quiz_term_catalog as $tax => $items ) {
				$catalog_name_to_slug[ $tax ] = array();
				foreach ( $items as $it ) {
					$catalog_name_to_slug[ $tax ][ (string) $it['name'] ] = (string) $it['slug'];
				}
			}

			foreach ( $quiz_products as $quiz_product_id ) {
				$normalized = array();

				foreach ( $quiz_term_catalog as $tax => $items ) {
					$current_terms = wp_get_object_terms(
						(int) $quiz_product_id,
						(string) $tax,
						array(
							'fields' => 'all',
						)
					);
					if ( is_wp_error( $current_terms ) || empty( $current_terms ) ) {
						continue;
					}

					$allowed_slugs = wp_list_pluck( $items, 'slug' );
					$term_for_product = array();
					foreach ( $current_terms as $ct ) {
						if ( in_array( (string) $ct->slug, $allowed_slugs, true ) ) {
							$term_for_product[] = array(
								'name' => (string) $ct->name,
								'slug' => (string) $ct->slug,
							);
							continue;
						}

						$mapped_slug = isset( $catalog_name_to_slug[ $tax ][ (string) $ct->name ] ) ? $catalog_name_to_slug[ $tax ][ (string) $ct->name ] : '';
						if ( '' !== $mapped_slug ) {
							$term_for_product[] = array(
								'name' => (string) $ct->name,
								'slug' => $mapped_slug,
							);
						}
					}

					if ( ! empty( $term_for_product ) ) {
						// Keep a single value per attribute for quiz compatibility.
						$normalized[ $tax ] = array( reset( $term_for_product ) );
					}
				}

				if ( ! empty( $normalized ) ) {
					$assign_global_attrs( (int) $quiz_product_id, $normalized );
				}
			}
		}

		$quiz_term_sets = array(
			array(
				'pa_gender'      => array( array( 'name' => 'مردانه', 'slug' => 'men' ) ),
				'pa_longevity'   => array( array( 'name' => 'بالا', 'slug' => 'high' ) ),
				'pa_occasion'    => array( array( 'name' => 'مهمانی', 'slug' => 'party' ) ),
				'pa_scent-family'=> array( array( 'name' => 'شرقی', 'slug' => 'oriental' ) ),
				'pa_season'      => array( array( 'name' => 'زمستان', 'slug' => 'winter' ) ),
			),
			array(
				'pa_gender'      => array( array( 'name' => 'زنانه', 'slug' => 'women' ) ),
				'pa_longevity'   => array( array( 'name' => 'متوسط', 'slug' => 'medium' ) ),
				'pa_occasion'    => array( array( 'name' => 'روزانه', 'slug' => 'daily' ) ),
				'pa_scent-family'=> array( array( 'name' => 'گلی', 'slug' => 'floral' ) ),
				'pa_season'      => array( array( 'name' => 'بهار', 'slug' => 'spring' ) ),
			),
			array(
				'pa_gender'      => array( array( 'name' => 'یونیسکس', 'slug' => 'unisex' ) ),
				'pa_longevity'   => array( array( 'name' => 'خیلی بالا', 'slug' => 'very-high' ) ),
				'pa_occasion'    => array( array( 'name' => 'اداری', 'slug' => 'office' ) ),
				'pa_scent-family'=> array( array( 'name' => 'چوبی', 'slug' => 'woody' ) ),
				'pa_season'      => array( array( 'name' => 'چهارفصل', 'slug' => 'all-season' ) ),
			),
			array(
				'pa_gender'      => array( array( 'name' => 'مردانه', 'slug' => 'men' ) ),
				'pa_longevity'   => array( array( 'name' => 'کم', 'slug' => 'low' ) ),
				'pa_occasion'    => array( array( 'name' => 'اسپرت', 'slug' => 'sport' ) ),
				'pa_scent-family'=> array( array( 'name' => 'خنک', 'slug' => 'fresh' ) ),
				'pa_season'      => array( array( 'name' => 'تابستان', 'slug' => 'summer' ) ),
			),
			array(
				'pa_gender'      => array( array( 'name' => 'زنانه', 'slug' => 'women' ) ),
				'pa_longevity'   => array( array( 'name' => 'بالا', 'slug' => 'high' ) ),
				'pa_occasion'    => array( array( 'name' => 'قرار', 'slug' => 'date' ) ),
				'pa_scent-family'=> array( array( 'name' => 'میوه‌ای', 'slug' => 'fruity' ) ),
				'pa_season'      => array( array( 'name' => 'پاییز', 'slug' => 'autumn' ) ),
			),
			array(
				'pa_gender'      => array( array( 'name' => 'یونیسکس', 'slug' => 'unisex' ) ),
				'pa_longevity'   => array( array( 'name' => 'متوسط', 'slug' => 'medium' ) ),
				'pa_occasion'    => array( array( 'name' => 'مهمانی', 'slug' => 'party' ) ),
				'pa_scent-family'=> array( array( 'name' => 'آروماتیک', 'slug' => 'aromatic' ) ),
				'pa_season'      => array( array( 'name' => 'زمستان', 'slug' => 'winter' ) ),
			),
		);

		foreach ( $quiz_term_sets as $i => $attr_terms ) {
			$product = new WC_Product_Simple();
			$product->set_name( 'محصول تست کوییز ' . ( $i + 1 ) );
			$product->set_slug( 'quiz-demo-' . ( $i + 1 ) );
			$product->set_status( 'publish' );
			$product->set_catalog_visibility( 'visible' );
			$product->set_regular_price( (string) ( 1500000 + ( $i * 250000 ) ) );
			$product->set_manage_stock( true );
			$product->set_stock_quantity( 50 );
			$product->set_stock_status( 'instock' );
			$base_sku = 'NOBLE-QUIZ-' . str_pad( (string) ( $i + 1 ), 3, '0', STR_PAD_LEFT );
			$sku      = $base_sku;
			if ( function_exists( 'wc_get_product_id_by_sku' ) ) {
				$attempt = 0;
				while ( wc_get_product_id_by_sku( $sku ) && $attempt < 25 ) {
					$attempt++;
					$sku = $base_sku . '-' . $attempt;
				}
			}
			$product->set_sku( $sku );
			$product->set_short_description( 'محصول دمو مخصوص تست فیلترهای کوییز (اتریبیوت‌های هسته‌ای).' );
			$product_id = $product->save();
			if ( ! $product_id ) {
				continue;
			}

			$assign_global_attrs( $product_id, $attr_terms );
			update_post_meta( $product_id, 'total_sales', wp_rand( 5, 60 ) );
			$quiz_created++;
		}

		update_option( 'noble_quiz_products_seeded', 1 );
	}

	if ( $seed_simple ) {
		foreach ( $products as $index => $item ) {
			$product = new WC_Product_Simple();
			$product->set_name( $item['name'] );
			$product->set_slug( sanitize_title( $item['name'] ) . '-' . ( $index + 1 ) );
			$product->set_status( 'publish' );
			$product->set_catalog_visibility( 'visible' );
			$product->set_regular_price( (string) $item['price'] );
			if ( ! empty( $item['sale'] ) ) {
				$product->set_sale_price( (string) $item['sale'] );
			}
			$product->set_manage_stock( true );
			$product->set_stock_quantity( (int) $item['stock'] );
			$product->set_stock_status( 'instock' );
			$product->set_sku( 'NOBLE-DEMO-' . str_pad( (string) ( $index + 1 ), 3, '0', STR_PAD_LEFT ) );
			$product->set_short_description( 'نمونه محصول دمو برای تست آرشیو و صفحه محصول در تم نوبل.' );
			$product->set_description(
				sprintf(
					'این محصول دمو برای تست کامل ظاهر فروشگاه ساخته شده است. خانواده بویایی: %1$s | حجم: %2$s | مناسب برای: %3$s | فصل پیشنهادی: %4$s',
					$item['family'],
					$item['volume'],
					$item['gender'],
					$item['season']
				)
			);

			$product->set_attributes(
				noble_build_product_attributes(
					array(
						'برند'            => $item['brand'],
						'حجم'             => $item['volume'],
						'جنسیت'           => $item['gender'],
						'خانواده بویایی'  => $item['family'],
						'فصل پیشنهادی'    => $item['season'],
					)
				)
			);

			$product_id = $product->save();
			if ( $product_id ) {
				if ( ! empty( $category_ids[ $item['category'] ] ) ) {
					wp_set_object_terms( $product_id, array( $category_ids[ $item['category'] ] ), 'product_cat' );
				}
				update_post_meta( $product_id, 'total_sales', wp_rand( 25, 240 ) );
				$simple_created++;
			}
		}
		update_option( 'noble_demo_products_seeded', 1 );
	}

	if ( $seed_variable ) {
		$variable_products = array(
			array( 'name' => 'نوبل سلکت دِی', 'brand' => 'NOBLE SIGNATURE', 'category' => 'روزانه و اداری', 'gender' => 'Unisex', 'family' => 'Fresh Citrus', 'season' => 'چهارفصل', 'variations' => array( array( 'volume' => '30ml', 'price' => 980000, 'sale' => 0, 'stock' => 20 ), array( 'volume' => '50ml', 'price' => 1490000, 'sale' => 1290000, 'stock' => 16 ), array( 'volume' => '100ml', 'price' => 2590000, 'sale' => 2290000, 'stock' => 10 ) ) ),
			array( 'name' => 'اوژن رز نویر', 'brand' => 'PRIVATE BLEND', 'category' => 'لوکس و خاص', 'gender' => 'Women', 'family' => 'Rose Oriental', 'season' => 'پاییز/زمستان', 'variations' => array( array( 'volume' => '30ml', 'price' => 1320000, 'sale' => 0, 'stock' => 14 ), array( 'volume' => '50ml', 'price' => 1980000, 'sale' => 1750000, 'stock' => 10 ), array( 'volume' => '100ml', 'price' => 3250000, 'sale' => 0, 'stock' => 7 ) ) ),
			array( 'name' => 'مارین کلاسیک', 'brand' => 'BLUE ATLAS', 'category' => 'خنک و دریایی', 'gender' => 'Men', 'family' => 'Marine Aromatic', 'season' => 'تابستان', 'variations' => array( array( 'volume' => '30ml', 'price' => 870000, 'sale' => 0, 'stock' => 22 ), array( 'volume' => '50ml', 'price' => 1390000, 'sale' => 1190000, 'stock' => 15 ), array( 'volume' => '100ml', 'price' => 2350000, 'sale' => 2090000, 'stock' => 11 ) ) ),
			array( 'name' => 'عنبر اسپایس ریزرو', 'brand' => 'LIMITED SELECT', 'category' => 'گرم و شرقی', 'gender' => 'Unisex', 'family' => 'Amber Spicy', 'season' => 'پاییز/زمستان', 'variations' => array( array( 'volume' => '30ml', 'price' => 1450000, 'sale' => 0, 'stock' => 12 ), array( 'volume' => '50ml', 'price' => 2180000, 'sale' => 1890000, 'stock' => 10 ), array( 'volume' => '100ml', 'price' => 3590000, 'sale' => 3190000, 'stock' => 8 ) ) ),
			array( 'name' => 'فلورال سافت تاچ', 'brand' => 'FLORA PRIVATE', 'category' => 'روزانه و اداری', 'gender' => 'Women', 'family' => 'Soft Floral', 'season' => 'بهار', 'variations' => array( array( 'volume' => '30ml', 'price' => 910000, 'sale' => 0, 'stock' => 18 ), array( 'volume' => '50ml', 'price' => 1520000, 'sale' => 1320000, 'stock' => 14 ), array( 'volume' => '100ml', 'price' => 2490000, 'sale' => 0, 'stock' => 9 ) ) ),
		);

		foreach ( $variable_products as $index => $item ) {
			$product = new WC_Product_Variable();
			$product->set_name( $item['name'] );
			$product->set_slug( sanitize_title( $item['name'] ) . '-var-' . ( $index + 1 ) );
			$product->set_status( 'publish' );
			$product->set_catalog_visibility( 'visible' );
			$product->set_sku( 'NOBLE-VAR-' . str_pad( (string) ( $index + 1 ), 3, '0', STR_PAD_LEFT ) );
			$product->set_short_description( 'نمونه محصول متغیر برای تست صفحات آرشیو و سینگل ووکامرس.' );
			$product->set_description( 'این محصول دارای چند حجم مختلف است و برای تست کامل Variation ها ساخته شده است.' );

			$volume_options = wp_list_pluck( $item['variations'], 'volume' );

			$volume_attribute = new WC_Product_Attribute();
			$volume_attribute->set_id( 0 );
			$volume_attribute->set_name( 'Volume' );
			$volume_attribute->set_options( $volume_options );
			$volume_attribute->set_visible( true );
			$volume_attribute->set_variation( true );

			$product->set_attributes(
				array_merge(
					noble_build_product_attributes(
						array(
							'برند'           => $item['brand'],
							'جنسیت'          => $item['gender'],
							'خانواده بویایی' => $item['family'],
							'فصل پیشنهادی'   => $item['season'],
						)
					),
					array( $volume_attribute )
				)
			);
			$product->set_default_attributes( array( 'Volume' => $volume_options[0] ) );

			$product_id = $product->save();
			if ( ! $product_id ) {
				continue;
			}

			if ( ! empty( $category_ids[ $item['category'] ] ) ) {
				wp_set_object_terms( $product_id, array( $category_ids[ $item['category'] ] ), 'product_cat' );
			}

			foreach ( $item['variations'] as $var_idx => $variation_data ) {
				$variation = new WC_Product_Variation();
				$variation->set_parent_id( $product_id );
				$variation->set_attributes( array( 'Volume' => $variation_data['volume'] ) );
				$variation->set_regular_price( (string) $variation_data['price'] );
				if ( ! empty( $variation_data['sale'] ) ) {
					$variation->set_sale_price( (string) $variation_data['sale'] );
				}
				$variation->set_manage_stock( true );
				$variation->set_stock_quantity( (int) $variation_data['stock'] );
				$variation->set_stock_status( 'instock' );
				$variation->set_sku( 'NOBLE-VAR-' . str_pad( (string) ( $index + 1 ), 3, '0', STR_PAD_LEFT ) . '-' . ( $var_idx + 1 ) );
				$variation->save();
			}

			WC_Product_Variable::sync( $product_id );
			update_post_meta( $product_id, 'total_sales', wp_rand( 30, 180 ) );
			$variable_created++;
		}

		update_option( 'noble_demo_variable_products_seeded', 1 );
	}

	wp_die(
		sprintf(
			/* translators: 1: simple count, 2: variable count, 3: quiz count */
			esc_html__( 'Done. %1$d simple products, %2$d variable products, and %3$d quiz products created.', 'noble-theme' ),
			(int) $simple_created,
			(int) $variable_created,
			(int) $quiz_created
		)
	);
}
add_action( 'admin_init', 'noble_seed_demo_products' );
