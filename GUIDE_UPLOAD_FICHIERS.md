# Guide d'utilisation - Upload de fichiers dans le chat

## Fonctionnalités implémentées

### ✅ Types de fichiers autorisés
- **Images** : JPG, JPEG, PNG, GIF
- **Documents** : PDF
- **Taille limite** : 5 Mo maximum

### ✅ Stockage
- **Local** : Fichiers stockés dans `storage/app/public/chat-files/{project_id}/`
- **Organisation** : Un dossier par projet pour une meilleure organisation
- **Sécurité** : Noms de fichiers uniques générés automatiquement

### ✅ Interface utilisateur

#### Upload de fichiers
1. Cliquer sur l'icône de trombone (📎) dans la zone de saisie
2. Sélectionner un fichier autorisé
3. Prévisualisation du fichier sélectionné avec :
   - Nom du fichier
   - Taille formatée
   - Icône selon le type (image ou PDF)
4. Possibilité de supprimer le fichier avant envoi

#### Affichage des fichiers

**Images (Style WhatsApp) :**
- ✅ **Affichage direct** dans le chat sans clic
- ✅ **Taille responsive** : s'adapte à l'écran (max-w-xs à max-w-lg)
- ✅ **Overlay informatif** : nom et taille au survol
- ✅ **Bouton téléchargement** flottant au survol
- ✅ **Modal plein écran** au clic avec :
  - Boutons d'action (télécharger, fermer)
  - Informations détaillées
  - Animations fluides
  - Navigation clavier (Escape)
- ✅ **Animations** : hover effects, transitions, slide-in
- ✅ **Design moderne** : ombres, gradients, backdrop-blur

**PDF :**
- Carte avec icône PDF
- Nom du fichier et taille
- Boutons de téléchargement et ouverture dans un nouvel onglet

### ✅ Fonctionnalités avancées

#### Validation côté client
- Vérification de la taille (5MB max)
- Vérification du type de fichier
- Messages d'erreur explicites

#### Validation côté serveur
- Double vérification de la taille et du type
- Stockage sécurisé avec noms uniques
- Gestion des erreurs

#### Gestion des fichiers
- Suppression automatique lors de la suppression d'un message
- Téléchargement sécurisé avec vérification des permissions
- Marque les messages comme lus lors du téléchargement

## Utilisation

### Pour les utilisateurs
1. **Ouvrir le chat** d'un projet : `/projects/{id}/chat-detached`
2. **Cliquer sur l'icône trombone** (📎) dans la zone de saisie
3. **Sélectionner une image ou un PDF** (max 5MB)
4. **Optionnel** : ajouter un message texte
5. **Envoyer** et voir le fichier s'afficher dans le chat !

### Expérience utilisateur optimisée
- **Images** : Affichage immédiat comme sur WhatsApp
- **Survol** : Informations et boutons apparaissent
- **Clic** : Modal plein écran pour voir en grand
- **Téléchargement** : Un clic pour sauvegarder
- **Responsive** : S'adapte à tous les écrans

## 🎨 Design et UX

### Animations et transitions
- **Hover effects** : Élévation et ombres
- **Slide-in** : Nouvelles images apparaissent en douceur
- **Modal animations** : Ouverture/fermeture fluide
- **Loading states** : Placeholders animés

### Responsive design
- **Mobile** : max-w-xs (petit écran)
- **Tablet** : max-w-sm (écran moyen)
- **Desktop** : max-w-md à max-w-lg (grand écran)

### Accessibilité
- **Clavier** : Escape pour fermer le modal
- **Clic** : Sur l'image ou l'arrière-plan pour fermer
- **Focus** : Navigation clavier dans le modal
- **Alt text** : Descriptions pour les images

## Pour les développeurs

#### Routes disponibles
```php
// Upload de message avec fichier
POST /projects/{project}/chat

// Téléchargement de fichier
GET /projects/{project}/chat/{message}/download
```

#### Modèle ProjectMessage
```php
// Nouveaux attributs
$message->filename          // Nom unique du fichier
$message->original_name     // Nom original du fichier
$message->file_path         // Chemin de stockage
$message->file_size         // Taille en bytes
$message->file_type         // Type MIME

// Attributs calculés
$message->has_file          // Booléen
$message->file_url          // URL publique
$message->formatted_file_size // Taille formatée
$message->isImage()         // Méthode pour images
$message->isPdf()           // Méthode pour PDF
```

## Sécurité

- Validation stricte des types de fichiers
- Limitation de taille côté client et serveur
- Noms de fichiers uniques pour éviter les conflits
- Vérification des permissions d'accès au projet
- Stockage dans un dossier séparé par projet

## Maintenance

### Nettoyage des fichiers
Les fichiers sont automatiquement supprimés lors de la suppression d'un message grâce à la méthode `deleteFile()` du modèle.

### Sauvegarde
Les fichiers sont stockés dans `storage/app/public/chat-files/` et peuvent être sauvegardés avec le reste de l'application.

## Limitations actuelles

- Pas de compression d'images automatique
- Pas de prévisualisation des PDF
- Pas de drag & drop (à implémenter)
- Pas de gestion des fichiers multiples (un seul par message)
- Pas de galerie d'images (navigation entre images)

## 🚀 Améliorations futures possibles

- **Compression automatique** des images
- **Galerie d'images** avec navigation
- **Drag & drop** pour l'upload
- **Fichiers multiples** par message
- **Prévisualisation PDF** intégrée
- **Recherche** dans les fichiers
- **Partage** de fichiers entre projets 