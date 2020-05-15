---
title: Authors

access:
    admin.authors: true
    admin.super: true
form:
  name: authors
  action: '/authors'
  template: authors
  refresh_prevention: true

  fields:
    authors:
      type: list
      display_label: false
      collapsed: true
      style: vertical
      help: "Add or edit author details"
      data-default@: ['\Grav\Plugin\AuraAuthorsPlugin::getAuthors']

      fields:
        .name:
          type: text
          size: large
          label: Name
          validate:
            required: true
        .label:
          type: text
          size: large
          label: Taxonomy Label
          validate:
            pattern: "[a-z][a-z0-9_\-]+"
            message: "Use all lowercase letters and replace spaces with hyphens."
            required: true
        .image:
          type: file
          size: large
          label: Image
          multiple: false
          destination: 'user/images'
          accept:
          - image/*
        .description:
          type: textarea
          size: long
          label: Description
        .person-facebook-url:
          type: text
          size: large
          label: Facebook URL
          placeholder: 'https://www.facebook.com/username'
        .person-twitter-user:
          type: text
          size: large
          label: Twitter Username
          placeholder: 'username'
        .person-instagram-url:
          type: text
          size: large
          label: Instagram URL
          placeholder: 'https://www.instagram.com/username'
        .person-linkedin-url:
          type: text
          size: large
          label: LinkedIn URL
          placeholder: 'https://www.linkedin.com/in/name'
        .person-pinterest-url:
          type: text
          size: large
          label: Pinterest URL
          placeholder: 'https://www.pinterest.com/user/username'
        .person-youtube-url:
          type: text
          size: large
          label: YouTube URL
          placeholder: 'https://www.youtube.com/username'
        .person-website-url:
          type: text
          label: Website URL
          placeholder: 'https://www.example.com'

  buttons:
    submit:
        type: submit
        value: ' Save'
        classes: button
        id: authorSave
---
