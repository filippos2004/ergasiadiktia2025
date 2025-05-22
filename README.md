# Εργασία Εξαμήνου στο μάθημα Τεχνολογίες Διαδικτύου

**Μάθημα:** Τεχνολογίες Διαδικτύου  
**Τμήμα:** Πληροφορικής, Ιόνιο Πανεπιστήμιο  
**Εξάμηνο ΣΤ**

**Μέλη Ομάδας:**  
- Φίλιππος Χατζηεργάτης (ΑΜ: inf2022230)  
- Δανιήλ Αριστάρχος Παυλίδης (ΑΜ: inf2022161)  
- Μιχάλης Βλαχάβας (ΑΜ: inf2022025)

---

## Περιγραφή Έργου

Ανάπτυξη δυναμικού ιστοτόπου αναπαραγωγής streaming περιεχομένου, με:
- Εγγραφή / Σύνδεση χρηστών (PHP + MySQL)
- Δημιουργία & διαχείριση λιστών βίντεο (Playlists)
- Αναζήτηση βίντεο στο YouTube μέσω YouTube Data API v3
- Προσθήκη βίντεο σε playlists & αναπαραγωγή μέσω ενσωματωμένου player
- Dockerized ανάπτυξη με Docker Compose

---

## Απαιτήσεις

- Docker ≥ 20.10  
- Docker Compose ≥ 1.29  
- (προαιρετικά) Docker Desktop ή Linux με εγκατεστημένο Docker Engine  

---

## Δομή Project

```
project-root/
├── Dockerfile
├── docker-compose.yml
├── mysql-init/
│   └── init.sql
└── src/
    ├── login.php
    ├── streamplay.php
    ├── main.php
    ├── profile.php
    ├── logout.php
    
    
```

---

## Χρήση

- **Εγγραφή-συνδεση**       http://localhost:8080/streamplay.php 
- **Κύρια Σελίδα:**  http://localhost:8080/main.php  
- **Profile:**        http://localhost:8080/profile.php   

Στη σελίδα **My Playlists**, μπορείτε να:
1. Δημιουργήσετε νέες playlists (public/private)  
2. Αναζητήσετε βίντεο στο YouTube και να τα προσθέσετε σε playlists  
3. Προβολή & αναπαραγωγή των βίντεο κάθε playlist  
4. Διαγραφή δικών σας playlists  

---


