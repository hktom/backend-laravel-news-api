- user
    id
    name
    email
    avatar

- article
    id
    title
    image
    url
    content
    bookmarked
    read_later
    user_id

- taxonomy
    id
    name
    type
    user_id
    taxonomy_id

- setting
    id
    disposition
    dark_mode
    notification
    feed :['category', 'source', 'author']

taxonomies example:
     -
        id: 1
        name: 'Tech'
        type: 'category'
        user_id: 1
        taxonomy_id: null
    
    - 
        id: 2
        name: 'TechCrunch'
        type: 'source'
        user_id: 1
        taxonomy_id: null

    - 
        id: 3
        name: 'TechCrunch'
        type: 'author'
        user_id: 1
        taxonomy_id: null

    - 
        id: 4
        name: 'Actuality'
        type: 'folder'
        user_id: 1
        taxonomy_id: null

    - 
        id: 5
        name: 'Tech'
        type: 'category'
        user_id: 1
        taxonomy_id: 4

    - 
        id: 6
        name: 'TechCrunch'
        type: 'source'
        user_id: 1
        taxonomy_id: 4